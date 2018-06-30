import axios from 'axios'
import state from './state';

    let debug = true;
    const log = function () {
        if(debug !== true){
            return false;
        }
        Array.prototype.unshift.call(arguments, '调试信息：');
        console.warn.apply(this, arguments);
        return true;
    };
    //格式化时间
    const getTime = function() {
        let now = new Date();
        let year = now.getFullYear();
        let month = now.getMonth()+1;
        let day = now.getDate();
        let hour = now.getHours();
        let minute = now.getMinutes();
        let second = now.getSeconds();
        month = month.length < 2 ?  "0" + month : month;
        day = day.length < 2 ?  "0" + day : day;
        return year+"-"+month+"-"+day+" "+hour+":"+minute+":"+second;
    };

    const getUrl = (function () {
        log('开始获取URL');
        let list = [],urls = {}, isGet = false;
        (function() {
            log('开始获取URL', '开始请求网络获取 URL列表');
            axios({
                method:'post',
                url: state.URL,
            }).then(function (res) {
                log(res);
                if(res.data.status === 0){
                    urls = res.data.result;
                    if(list.length !== 0){
                        log('获取 URL 列表完成，遍历list 中的回调函数', list);
                        list.forEach(function (v, k) {
                            let name = undefined;
                            if(v.name){
                                name = urls[v.name];
                            }
                            v.call(state, urls, name);
                        });
                        list = [];
                    }
                }
            }).catch(function (res) {
                log(res);
            }).finally(function (){
                log('请求URL列表最后', arguments);
                isGet = true;
            });
        }());
        return function (callfunction) {
            if(isGet){
                log('已经请求过 URL');
                let url;
                if(typeof callfunction === 'string' && (url = urls[callfunction])){
                    log('传过来的是 字符串', callfunction);
                    return url;
                }
                if(typeof callfunction === 'function'){
                    log('传过来的是 回调', callfunction);
                    url = undefined;
                    if(callfunction.name){
                        url = urls[callfunction.name];
                    }
                    callfunction.call(state, urls, url);
                }
                return urls;
            }
            log('未请求过 URL');
            if(typeof callfunction === 'function'){
                log('传过来的是 回调 加入列表', callfunction);
                list.push(callfunction);
                return true;
            }
            return false;
        };
    }());

    /**
     * 网络兼容方式分析
     *
     * 1. 至少返回的东西中含有 then 方法 和 catch 方法 还有 finally 方法
     *      这样才能和 原有 axios 兼容
     *      并且参数 传递到 原有 axios 的对应方法上
     * 2. 实现方式
     *      由于获取URL 使用的使用的是回调函数，不能使用立即返回。
     *      1. 返回一个对象其中有 以上三个方法，通过这样接受到 指定方法的参数 放置到 aixos对象 上，这样实现了简易的桥
     *          1）保证返回的时候不能请求网络
     *          2）使用类 还是直接 用 {}; 我的意思是 使用类 学习新的语法。
     *      2. 给getUrl 设置 以上方法实现转接
     *          还是想使用上面的方法来转接，至少想对我比较好理解。
     */

    /**
     * 给 axios 添加一个获取 URL连接后才调用的桥
     */
    class network {
        /**
         * 构造函数 兼容 _axios 写法
         * @param options
         */
        constructor (options) {
            if (Object.prototype.toString.call(options) !== "[object Object]" || !options.act) throw new Error('请传入对象，或者设置 act 属性');
            this.url = options.act;
            log('new network', this.url, options);
            delete options.act;
            setTimeout(function () {
                getUrl(function (urls, url) {
                    url = url || urls[this.url];
                    axios({
                        url,
                        data:options,
                        method:'POST'
                    }).then(function (res) {
                        log('network 成功了', this.url, res);
                        if(res.data.status !== 0) {
                            this.isFunction(this._catch) && this._catch(new Error('请求成功 状态出错'));
                            return false;
                        }
                        if(!res.data.data){
                            res.data.data = res.data.result;
                        }
                        this.isFunction(this._then) && this._then(res);
                    }.bind(this)).catch(function (err) {
                        log('network 出错了', this.url, err);
                        this.isFunction(this._catch) && this._catch(err);
                    }.bind(this)).finally(function (res) {
                        log('network 完成了', this.url, res);
                        this.isFunction(this._finally) && this._finally(res);
                    }.bind(this));
                }.bind(this));
            }.bind(this), 0);
        };

        /**
         * then 方法 兼容 axios 的方法，这里只是转接桥，核心函数在构造方法中。下同
         * @param fn
         * @returns {network}
         */
        then (fn) {
            this._then = fn;
            return this;
        };

        /**
         * catch 方法
         * @param fn            回调函数
         * @returns {network}   自身 方便调用
         */
        catch(fn) {
            this._catch = fn;
            return this;
        };

        /**
         * finally 方法
         * @param fn           回调函数
         * @returns {network}  自身 方便调用
         */
        finally(fn){
            this._finally =fn;
            return this;
        };

        /**
         * 判断是不是函数方法
         * @param arg
         * @returns {boolean}
         */
        isFunction (arg) {
            return Object.prototype.toString.call(arg) === '[object Function]';
        }
    }

    /**
     * 重写 _axios 做一个简易桥 实现我的后台想法
     * @param parms
     * @returns {network}
     * @private
     */
    const _ax = parms => {
        return new network(parms);
    };

    export {getTime, getUrl, log, _ax};