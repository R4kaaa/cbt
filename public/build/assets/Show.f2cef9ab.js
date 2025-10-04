import { L as Et } from "./Student.0f4b62bc.js";
import { E as kt } from "./Editor.d079d9f6.js";
import { x as Ct, a1 as St, a5 as At, H as Tt, L as Rt, m as I, E as Mt, P as jt, a as re, o as p, c as b, b as oe, w as Ne, d as a, t as g, i as y, n as H, g as E, F as Q, p as ue, e as S, h as B, j as Ot } from "./app.c6958972.js";
import { S as j } from "./sweetalert2.all.1d02a522.js";
import { _ as Nt } from "./_plugin-vue_export-helper.cdc0426e.js"; /*! vue-countdown v2.0.0 | (c) 2018-present Chen Fengyuan | MIT */
const X = 1e3,
    K = 60 * X,
    G = 60 * K,
    Le = 24 * G,
    Pe = "abort",
    Be = "end",
    Ie = "progress",
    Ue = "start",
    De = "visibilitychange";
var Lt = Ct({
        name: "VueCountdown",
        props: { autoStart: { type: Boolean, default: !0 }, emitEvents: { type: Boolean, default: !0 }, interval: { type: Number, default: 1e3, validator: e => e >= 0 }, now: { type: Function, default: () => Date.now() }, tag: { type: String, default: "span" }, time: { type: Number, default: 0, validator: e => e >= 0 }, transform: { type: Function, default: e => e } },
        emits: [Pe, Be, Ie, Ue],
        data() { return { counting: !1, endTime: 0, totalMilliseconds: 0, requestId: 0 } },
        computed: { days() { return Math.floor(this.totalMilliseconds / Le) }, hours() { return Math.floor(this.totalMilliseconds % Le / G) }, minutes() { return Math.floor(this.totalMilliseconds % G / K) }, seconds() { return Math.floor(this.totalMilliseconds % K / X) }, milliseconds() { return Math.floor(this.totalMilliseconds % X) }, totalDays() { return this.days }, totalHours() { return Math.floor(this.totalMilliseconds / G) }, totalMinutes() { return Math.floor(this.totalMilliseconds / K) }, totalSeconds() { return Math.floor(this.totalMilliseconds / X) } },
        watch: { $props: { deep: !0, immediate: !0, handler() { this.totalMilliseconds = this.time, this.endTime = this.now() + this.time, this.autoStart && this.start() } } },
        mounted() { document.addEventListener(De, this.handleVisibilityChange) },
        beforeUnmount() { document.removeEventListener(De, this.handleVisibilityChange), this.pause() },
        methods: {
            start() { this.counting || (this.counting = !0, this.emitEvents && this.$emit(Ue), document.visibilityState === "visible" && this.continue()) },
            continue () {
                if (!this.counting) return;
                const e = Math.min(this.totalMilliseconds, this.interval);
                if (e > 0) {
                    let t, n;
                    const s = i => {
                        t || (t = i), n || (n = i);
                        const u = i - t;
                        u >= e || u + (i - n) / 2 >= e ? this.progress() : this.requestId = requestAnimationFrame(s), n = i
                    };
                    this.requestId = requestAnimationFrame(s)
                } else this.end()
            },
            pause() { cancelAnimationFrame(this.requestId) },
            progress() {!this.counting || (this.totalMilliseconds -= this.interval, this.emitEvents && this.totalMilliseconds > 0 && this.$emit(Ie, { days: this.days, hours: this.hours, minutes: this.minutes, seconds: this.seconds, milliseconds: this.milliseconds, totalDays: this.totalDays, totalHours: this.totalHours, totalMinutes: this.totalMinutes, totalSeconds: this.totalSeconds, totalMilliseconds: this.totalMilliseconds }), this.continue()) },
            abort() {!this.counting || (this.pause(), this.counting = !1, this.emitEvents && this.$emit(Pe)) },
            end() {!this.counting || (this.pause(), this.totalMilliseconds = 0, this.counting = !1, this.emitEvents && this.$emit(Be)) },
            update() { this.counting && (this.totalMilliseconds = Math.max(0, this.endTime - this.now())) },
            handleVisibilityChange() {
                switch (document.visibilityState) {
                    case "visible":
                        this.update(), this.continue();
                        break;
                    case "hidden":
                        this.pause();
                        break
                }
            }
        },
        render() { return St(this.tag, this.$slots.default ? [this.$slots.default(this.transform({ days: this.days, hours: this.hours, minutes: this.minutes, seconds: this.seconds, milliseconds: this.milliseconds, totalDays: this.totalDays, totalHours: this.totalHours, totalMinutes: this.totalMinutes, totalSeconds: this.totalSeconds, totalMilliseconds: this.totalMilliseconds }))] : void 0) }
    }),
    dt = { exports: {} },
    Te = { exports: {} },
    ct = function(t, n) { return function() { for (var i = new Array(arguments.length), u = 0; u < i.length; u++) i[u] = arguments[u]; return t.apply(n, i) } },
    Pt = ct,
    O = Object.prototype.toString;

function Re(e) { return Array.isArray(e) }

function Se(e) { return typeof e > "u" }

function Bt(e) { return e !== null && !Se(e) && e.constructor !== null && !Se(e.constructor) && typeof e.constructor.isBuffer == "function" && e.constructor.isBuffer(e) }

function ft(e) { return O.call(e) === "[object ArrayBuffer]" }

function It(e) { return O.call(e) === "[object FormData]" }

function Ut(e) { var t; return typeof ArrayBuffer < "u" && ArrayBuffer.isView ? t = ArrayBuffer.isView(e) : t = e && e.buffer && ft(e.buffer), t }

function Dt(e) { return typeof e == "string" }

function Ht(e) { return typeof e == "number" }

function ht(e) { return e !== null && typeof e == "object" }

function Y(e) { if (O.call(e) !== "[object Object]") return !1; var t = Object.getPrototypeOf(e); return t === null || t === Object.prototype }

function Ft(e) { return O.call(e) === "[object Date]" }

function Jt(e) { return O.call(e) === "[object File]" }

function zt(e) { return O.call(e) === "[object Blob]" }

function mt(e) { return O.call(e) === "[object Function]" }

function Vt(e) { return ht(e) && mt(e.pipe) }

function Qt(e) { return O.call(e) === "[object URLSearchParams]" }

function Wt(e) { return e.trim ? e.trim() : e.replace(/^\s+|\s+$/g, "") }

function Xt() { return typeof navigator < "u" && (navigator.product === "ReactNative" || navigator.product === "NativeScript" || navigator.product === "NS") ? !1 : typeof window < "u" && typeof document < "u" }

function Me(e, t) {
    if (!(e === null || typeof e > "u"))
        if (typeof e != "object" && (e = [e]), Re(e))
            for (var n = 0, s = e.length; n < s; n++) t.call(null, e[n], n, e);
        else
            for (var i in e) Object.prototype.hasOwnProperty.call(e, i) && t.call(null, e[i], i, e)
}

function Ae() {
    var e = {};

    function t(i, u) { Y(e[u]) && Y(i) ? e[u] = Ae(e[u], i) : Y(i) ? e[u] = Ae({}, i) : Re(i) ? e[u] = i.slice() : e[u] = i }
    for (var n = 0, s = arguments.length; n < s; n++) Me(arguments[n], t);
    return e
}

function Kt(e, t, n) { return Me(t, function(i, u) { n && typeof i == "function" ? e[u] = Pt(i, n) : e[u] = i }), e }

function Gt(e) { return e.charCodeAt(0) === 65279 && (e = e.slice(1)), e }
var x = { isArray: Re, isArrayBuffer: ft, isBuffer: Bt, isFormData: It, isArrayBufferView: Ut, isString: Dt, isNumber: Ht, isObject: ht, isPlainObject: Y, isUndefined: Se, isDate: Ft, isFile: Jt, isBlob: zt, isFunction: mt, isStream: Vt, isURLSearchParams: Qt, isStandardBrowserEnv: Xt, forEach: Me, merge: Ae, extend: Kt, trim: Wt, stripBOM: Gt },
    U = x;

function He(e) { return encodeURIComponent(e).replace(/%3A/gi, ":").replace(/%24/g, "$").replace(/%2C/gi, ",").replace(/%20/g, "+").replace(/%5B/gi, "[").replace(/%5D/gi, "]") }
var _t = function(t, n, s) {
        if (!n) return t;
        var i;
        if (s) i = s(n);
        else if (U.isURLSearchParams(n)) i = n.toString();
        else {
            var u = [];
            U.forEach(n, function(f, r) { f === null || typeof f > "u" || (U.isArray(f) ? r = r + "[]" : f = [f], U.forEach(f, function(l) { U.isDate(l) ? l = l.toISOString() : U.isObject(l) && (l = JSON.stringify(l)), u.push(He(r) + "=" + He(l)) })) }), i = u.join("&")
        }
        if (i) {
            var c = t.indexOf("#");
            c !== -1 && (t = t.slice(0, c)), t += (t.indexOf("?") === -1 ? "?" : "&") + i
        }
        return t
    },
    Yt = x;

function $() { this.handlers = [] }
$.prototype.use = function(t, n, s) { return this.handlers.push({ fulfilled: t, rejected: n, synchronous: s ? s.synchronous : !1, runWhen: s ? s.runWhen : null }), this.handlers.length - 1 };
$.prototype.eject = function(t) { this.handlers[t] && (this.handlers[t] = null) };
$.prototype.forEach = function(t) { Yt.forEach(this.handlers, function(s) { s !== null && t(s) }) };
var Zt = $,
    $t = x,
    en = function(t, n) { $t.forEach(t, function(i, u) { u !== n && u.toUpperCase() === n.toUpperCase() && (t[n] = i, delete t[u]) }) },
    vt = function(t, n, s, i, u) { return t.config = n, s && (t.code = s), t.request = i, t.response = u, t.isAxiosError = !0, t.toJSON = function() { return { message: this.message, name: this.name, description: this.description, number: this.number, fileName: this.fileName, lineNumber: this.lineNumber, columnNumber: this.columnNumber, stack: this.stack, config: this.config, code: this.code, status: this.response && this.response.status ? this.response.status : null } }, t },
    le, Fe;

function pt() {
    if (Fe) return le;
    Fe = 1;
    var e = vt;
    return le = function(n, s, i, u, c) { var o = new Error(n); return e(o, s, i, u, c) }, le
}
var de, Je;

function tn() {
    if (Je) return de;
    Je = 1;
    var e = pt();
    return de = function(n, s, i) { var u = i.config.validateStatus;!i.status || !u || u(i.status) ? n(i) : s(e("Request failed with status code " + i.status, i.config, null, i.request, i)) }, de
}
var ce, ze;

function nn() {
    if (ze) return ce;
    ze = 1;
    var e = x;
    return ce = e.isStandardBrowserEnv() ? function() {
        return {
            write: function(s, i, u, c, o, f) {
                var r = [];
                r.push(s + "=" + encodeURIComponent(i)), e.isNumber(u) && r.push("expires=" + new Date(u).toGMTString()), e.isString(c) && r.push("path=" + c), e.isString(o) && r.push("domain=" + o), f === !0 && r.push("secure"), document.cookie = r.join("; ")
            },
            read: function(s) { var i = document.cookie.match(new RegExp("(^|;\\s*)(" + s + ")=([^;]*)")); return i ? decodeURIComponent(i[3]) : null },
            remove: function(s) { this.write(s, "", Date.now() - 864e5) }
        }
    }() : function() { return { write: function() {}, read: function() { return null }, remove: function() {} } }(), ce
}
var fe, Ve;

function sn() { return Ve || (Ve = 1, fe = function(t) { return /^([a-z][a-z\d+\-.]*:)?\/\//i.test(t) }), fe }
var he, Qe;

function an() { return Qe || (Qe = 1, he = function(t, n) { return n ? t.replace(/\/+$/, "") + "/" + n.replace(/^\/+/, "") : t }), he }
var me, We;

function rn() {
    if (We) return me;
    We = 1;
    var e = sn(),
        t = an();
    return me = function(s, i) { return s && !e(i) ? t(s, i) : i }, me
}
var _e, Xe;

function on() {
    if (Xe) return _e;
    Xe = 1;
    var e = x,
        t = ["age", "authorization", "content-length", "content-type", "etag", "expires", "from", "host", "if-modified-since", "if-unmodified-since", "last-modified", "location", "max-forwards", "proxy-authorization", "referer", "retry-after", "user-agent"];
    return _e = function(s) {
        var i = {},
            u, c, o;
        return s && e.forEach(s.split(`
`), function(r) {
            if (o = r.indexOf(":"), u = e.trim(r.substr(0, o)).toLowerCase(), c = e.trim(r.substr(o + 1)), u) {
                if (i[u] && t.indexOf(u) >= 0) return;
                u === "set-cookie" ? i[u] = (i[u] ? i[u] : []).concat([c]) : i[u] = i[u] ? i[u] + ", " + c : c
            }
        }), i
    }, _e
}
var ve, Ke;

function un() {
    if (Ke) return ve;
    Ke = 1;
    var e = x;
    return ve = e.isStandardBrowserEnv() ? function() {
        var n = /(msie|trident)/i.test(navigator.userAgent),
            s = document.createElement("a"),
            i;

        function u(c) { var o = c; return n && (s.setAttribute("href", o), o = s.href), s.setAttribute("href", o), { href: s.href, protocol: s.protocol ? s.protocol.replace(/:$/, "") : "", host: s.host, search: s.search ? s.search.replace(/^\?/, "") : "", hash: s.hash ? s.hash.replace(/^#/, "") : "", hostname: s.hostname, port: s.port, pathname: s.pathname.charAt(0) === "/" ? s.pathname : "/" + s.pathname } }
        return i = u(window.location.href),
            function(o) { var f = e.isString(o) ? u(o) : o; return f.protocol === i.protocol && f.host === i.host }
    }() : function() { return function() { return !0 } }(), ve
}
var pe, Ge;

function ee() {
    if (Ge) return pe;
    Ge = 1;

    function e(t) { this.message = t }
    return e.prototype.toString = function() { return "Cancel" + (this.message ? ": " + this.message : "") }, e.prototype.__CANCEL__ = !0, pe = e, pe
}
var be, Ye;

function Ze() {
    if (Ye) return be;
    Ye = 1;
    var e = x,
        t = tn(),
        n = nn(),
        s = _t,
        i = rn(),
        u = on(),
        c = un(),
        o = pt(),
        f = te(),
        r = ee();
    return be = function(l) {
        return new Promise(function(w, C) {
            var N = l.data,
                L = l.headers,
                P = l.responseType,
                R;

            function J() { l.cancelToken && l.cancelToken.unsubscribe(R), l.signal && l.signal.removeEventListener("abort", R) }
            e.isFormData(N) && delete L["Content-Type"];
            var m = new XMLHttpRequest;
            if (l.auth) {
                var se = l.auth.username || "",
                    ie = l.auth.password ? unescape(encodeURIComponent(l.auth.password)) : "";
                L.Authorization = "Basic " + btoa(se + ":" + ie)
            }
            var z = i(l.baseURL, l.url);
            m.open(l.method.toUpperCase(), s(z, l.params, l.paramsSerializer), !0), m.timeout = l.timeout;

            function V() {
                if (!!m) {
                    var d = "getAllResponseHeaders" in m ? u(m.getAllResponseHeaders()) : null,
                        _ = !P || P === "text" || P === "json" ? m.responseText : m.response,
                        v = { data: _, status: m.status, statusText: m.statusText, headers: d, config: l, request: m };
                    t(function(T) { w(T), J() }, function(T) { C(T), J() }, v), m = null
                }
            }
            if ("onloadend" in m ? m.onloadend = V : m.onreadystatechange = function() {!m || m.readyState !== 4 || m.status === 0 && !(m.responseURL && m.responseURL.indexOf("file:") === 0) || setTimeout(V) }, m.onabort = function() {!m || (C(o("Request aborted", l, "ECONNABORTED", m)), m = null) }, m.onerror = function() { C(o("Network Error", l, null, m)), m = null }, m.ontimeout = function() {
                    var _ = l.timeout ? "timeout of " + l.timeout + "ms exceeded" : "timeout exceeded",
                        v = l.transitional || f.transitional;
                    l.timeoutErrorMessage && (_ = l.timeoutErrorMessage), C(o(_, l, v.clarifyTimeoutError ? "ETIMEDOUT" : "ECONNABORTED", m)), m = null
                }, e.isStandardBrowserEnv()) {
                var ae = (l.withCredentials || c(z)) && l.xsrfCookieName ? n.read(l.xsrfCookieName) : void 0;
                ae && (L[l.xsrfHeaderName] = ae)
            }
            "setRequestHeader" in m && e.forEach(L, function(_, v) { typeof N > "u" && v.toLowerCase() === "content-type" ? delete L[v] : m.setRequestHeader(v, _) }), e.isUndefined(l.withCredentials) || (m.withCredentials = !!l.withCredentials), P && P !== "json" && (m.responseType = l.responseType), typeof l.onDownloadProgress == "function" && m.addEventListener("progress", l.onDownloadProgress), typeof l.onUploadProgress == "function" && m.upload && m.upload.addEventListener("progress", l.onUploadProgress), (l.cancelToken || l.signal) && (R = function(d) {!m || (C(!d || d && d.type ? new r("canceled") : d), m.abort(), m = null) }, l.cancelToken && l.cancelToken.subscribe(R), l.signal && (l.signal.aborted ? R() : l.signal.addEventListener("abort", R))), N || (N = null), m.send(N)
        })
    }, be
}
var ye, $e;

function te() {
    if ($e) return ye;
    $e = 1;
    var e = x,
        t = en,
        n = vt,
        s = { "Content-Type": "application/x-www-form-urlencoded" };

    function i(f, r) {!e.isUndefined(f) && e.isUndefined(f["Content-Type"]) && (f["Content-Type"] = r) }

    function u() { var f; return (typeof XMLHttpRequest < "u" || typeof process < "u" && Object.prototype.toString.call(process) === "[object process]") && (f = Ze()), f }

    function c(f, r, h) {
        if (e.isString(f)) try { return (r || JSON.parse)(f), e.trim(f) } catch (l) { if (l.name !== "SyntaxError") throw l }
        return (h || JSON.stringify)(f)
    }
    var o = {
        transitional: { silentJSONParsing: !0, forcedJSONParsing: !0, clarifyTimeoutError: !1 },
        adapter: u(),
        transformRequest: [function(r, h) { return t(h, "Accept"), t(h, "Content-Type"), e.isFormData(r) || e.isArrayBuffer(r) || e.isBuffer(r) || e.isStream(r) || e.isFile(r) || e.isBlob(r) ? r : e.isArrayBufferView(r) ? r.buffer : e.isURLSearchParams(r) ? (i(h, "application/x-www-form-urlencoded;charset=utf-8"), r.toString()) : e.isObject(r) || h && h["Content-Type"] === "application/json" ? (i(h, "application/json"), c(r)) : r }],
        transformResponse: [function(r) {
            var h = this.transitional || o.transitional,
                l = h && h.silentJSONParsing,
                q = h && h.forcedJSONParsing,
                w = !l && this.responseType === "json";
            if (w || q && e.isString(r) && r.length) try { return JSON.parse(r) } catch (C) { if (w) throw C.name === "SyntaxError" ? n(C, this, "E_JSON_PARSE") : C }
            return r
        }],
        timeout: 0,
        xsrfCookieName: "XSRF-TOKEN",
        xsrfHeaderName: "X-XSRF-TOKEN",
        maxContentLength: -1,
        maxBodyLength: -1,
        validateStatus: function(r) { return r >= 200 && r < 300 },
        headers: { common: { Accept: "application/json, text/plain, */*" } }
    };
    return e.forEach(["delete", "get", "head"], function(r) { o.headers[r] = {} }), e.forEach(["post", "put", "patch"], function(r) { o.headers[r] = e.merge(s) }), ye = o, ye
}
var ln = x,
    dn = te(),
    cn = function(t, n, s) { var i = this || dn; return ln.forEach(s, function(c) { t = c.call(i, t, n) }), t },
    ge, et;

function bt() { return et || (et = 1, ge = function(t) { return !!(t && t.__CANCEL__) }), ge }
var tt = x,
    we = cn,
    fn = bt(),
    hn = te(),
    mn = ee();

function xe(e) { if (e.cancelToken && e.cancelToken.throwIfRequested(), e.signal && e.signal.aborted) throw new mn("canceled") }
var _n = function(t) { xe(t), t.headers = t.headers || {}, t.data = we.call(t, t.data, t.headers, t.transformRequest), t.headers = tt.merge(t.headers.common || {}, t.headers[t.method] || {}, t.headers), tt.forEach(["delete", "get", "head", "post", "put", "patch", "common"], function(i) { delete t.headers[i] }); var n = t.adapter || hn.adapter; return n(t).then(function(i) { return xe(t), i.data = we.call(t, i.data, i.headers, t.transformResponse), i }, function(i) { return fn(i) || (xe(t), i && i.response && (i.response.data = we.call(t, i.response.data, i.response.headers, t.transformResponse))), Promise.reject(i) }) },
    k = x,
    yt = function(t, n) {
        n = n || {};
        var s = {};

        function i(h, l) { return k.isPlainObject(h) && k.isPlainObject(l) ? k.merge(h, l) : k.isPlainObject(l) ? k.merge({}, l) : k.isArray(l) ? l.slice() : l }

        function u(h) { if (k.isUndefined(n[h])) { if (!k.isUndefined(t[h])) return i(void 0, t[h]) } else return i(t[h], n[h]) }

        function c(h) { if (!k.isUndefined(n[h])) return i(void 0, n[h]) }

        function o(h) { if (k.isUndefined(n[h])) { if (!k.isUndefined(t[h])) return i(void 0, t[h]) } else return i(void 0, n[h]) }

        function f(h) { if (h in n) return i(t[h], n[h]); if (h in t) return i(void 0, t[h]) }
        var r = { url: c, method: c, data: c, baseURL: o, transformRequest: o, transformResponse: o, paramsSerializer: o, timeout: o, timeoutMessage: o, withCredentials: o, adapter: o, responseType: o, xsrfCookieName: o, xsrfHeaderName: o, onUploadProgress: o, onDownloadProgress: o, decompress: o, maxContentLength: o, maxBodyLength: o, transport: o, httpAgent: o, httpsAgent: o, cancelToken: o, socketPath: o, responseEncoding: o, validateStatus: f };
        return k.forEach(Object.keys(t).concat(Object.keys(n)), function(l) {
            var q = r[l] || u,
                w = q(l);
            k.isUndefined(w) && q !== f || (s[l] = w)
        }), s
    },
    qe, nt;

function gt() { return nt || (nt = 1, qe = { version: "0.25.0" }), qe }
var vn = gt().version,
    je = {};
["object", "boolean", "number", "function", "string", "symbol"].forEach(function(e, t) { je[e] = function(s) { return typeof s === e || "a" + (t < 1 ? "n " : " ") + e } });
var st = {};
je.transitional = function(t, n, s) {
    function i(u, c) { return "[Axios v" + vn + "] Transitional option '" + u + "'" + c + (s ? ". " + s : "") }
    return function(u, c, o) { if (t === !1) throw new Error(i(c, " has been removed" + (n ? " in " + n : ""))); return n && !st[c] && (st[c] = !0, console.warn(i(c, " has been deprecated since v" + n + " and will be removed in the near future"))), t ? t(u, c, o) : !0 }
};

function pn(e, t, n) {
    if (typeof e != "object") throw new TypeError("options must be an object");
    for (var s = Object.keys(e), i = s.length; i-- > 0;) {
        var u = s[i],
            c = t[u];
        if (c) {
            var o = e[u],
                f = o === void 0 || c(o, u, e);
            if (f !== !0) throw new TypeError("option " + u + " must be " + f);
            continue
        }
        if (n !== !0) throw Error("Unknown option " + u)
    }
}
var bn = { assertOptions: pn, validators: je },
    wt = x,
    yn = _t,
    it = Zt,
    at = _n,
    ne = yt,
    xt = bn,
    D = xt.validators;

function F(e) { this.defaults = e, this.interceptors = { request: new it, response: new it } }
F.prototype.request = function(t, n) {
    if (typeof t == "string" ? (n = n || {}, n.url = t) : n = t || {}, !n.url) throw new Error("Provided config url is not valid");
    n = ne(this.defaults, n), n.method ? n.method = n.method.toLowerCase() : this.defaults.method ? n.method = this.defaults.method.toLowerCase() : n.method = "get";
    var s = n.transitional;
    s !== void 0 && xt.assertOptions(s, { silentJSONParsing: D.transitional(D.boolean), forcedJSONParsing: D.transitional(D.boolean), clarifyTimeoutError: D.transitional(D.boolean) }, !1);
    var i = [],
        u = !0;
    this.interceptors.request.forEach(function(w) { typeof w.runWhen == "function" && w.runWhen(n) === !1 || (u = u && w.synchronous, i.unshift(w.fulfilled, w.rejected)) });
    var c = [];
    this.interceptors.response.forEach(function(w) { c.push(w.fulfilled, w.rejected) });
    var o;
    if (!u) { var f = [at, void 0]; for (Array.prototype.unshift.apply(f, i), f = f.concat(c), o = Promise.resolve(n); f.length;) o = o.then(f.shift(), f.shift()); return o }
    for (var r = n; i.length;) {
        var h = i.shift(),
            l = i.shift();
        try { r = h(r) } catch (q) { l(q); break }
    }
    try { o = at(r) } catch (q) { return Promise.reject(q) }
    for (; c.length;) o = o.then(c.shift(), c.shift());
    return o
};
F.prototype.getUri = function(t) { if (!t.url) throw new Error("Provided config url is not valid"); return t = ne(this.defaults, t), yn(t.url, t.params, t.paramsSerializer).replace(/^\?/, "") };
wt.forEach(["delete", "get", "head", "options"], function(t) { F.prototype[t] = function(n, s) { return this.request(ne(s || {}, { method: t, url: n, data: (s || {}).data })) } });
wt.forEach(["post", "put", "patch"], function(t) { F.prototype[t] = function(n, s, i) { return this.request(ne(i || {}, { method: t, url: n, data: s })) } });
var gn = F,
    Ee, rt;

function wn() {
    if (rt) return Ee;
    rt = 1;
    var e = ee();

    function t(n) {
        if (typeof n != "function") throw new TypeError("executor must be a function.");
        var s;
        this.promise = new Promise(function(c) { s = c });
        var i = this;
        this.promise.then(function(u) {
            if (!!i._listeners) {
                var c, o = i._listeners.length;
                for (c = 0; c < o; c++) i._listeners[c](u);
                i._listeners = null
            }
        }), this.promise.then = function(u) { var c, o = new Promise(function(f) { i.subscribe(f), c = f }).then(u); return o.cancel = function() { i.unsubscribe(c) }, o }, n(function(c) { i.reason || (i.reason = new e(c), s(i.reason)) })
    }
    return t.prototype.throwIfRequested = function() { if (this.reason) throw this.reason }, t.prototype.subscribe = function(s) {
        if (this.reason) { s(this.reason); return }
        this._listeners ? this._listeners.push(s) : this._listeners = [s]
    }, t.prototype.unsubscribe = function(s) {
        if (!!this._listeners) {
            var i = this._listeners.indexOf(s);
            i !== -1 && this._listeners.splice(i, 1)
        }
    }, t.source = function() { var s, i = new t(function(c) { s = c }); return { token: i, cancel: s } }, Ee = t, Ee
}
var ke, ot;

function xn() { return ot || (ot = 1, ke = function(t) { return function(s) { return t.apply(null, s) } }), ke }
var Ce, ut;

function qn() {
    if (ut) return Ce;
    ut = 1;
    var e = x;
    return Ce = function(n) { return e.isObject(n) && n.isAxiosError === !0 }, Ce
}
var lt = x,
    En = ct,
    Z = gn,
    kn = yt,
    Cn = te();

function qt(e) {
    var t = new Z(e),
        n = En(Z.prototype.request, t);
    return lt.extend(n, Z.prototype, t), lt.extend(n, t), n.create = function(i) { return qt(kn(e, i)) }, n
}
var A = qt(Cn);
A.Axios = Z;
A.Cancel = ee();
A.CancelToken = wn();
A.isCancel = bt();
A.VERSION = gt().version;
A.all = function(t) { return Promise.all(t) };
A.spread = xn();
A.isAxiosError = qn();
Te.exports = A;
Te.exports.default = A;
(function(e) { e.exports = Te.exports })(dt);
const W = At(dt.exports);
const Sn = {
        layout: Et,
        components: { Head: Tt, Link: Rt, VueCountdown: Lt, Editor: kt },
        props: { id: Number, page: Number, exam_group: Object, all_questions: Array, question_answered: Number, question_active: Object, answer_order: Array, duration: Object },
        setup(e) {
            let t = ["A", "B", "C", "D", "E"];
            const n = { a: "\u3042", i: "\u3044", u: "\u3046", e: "\u3048", o: "\u304A", ka: "\u304B", ki: "\u304D", ku: "\u304F", ke: "\u3051", ko: "\u3053", ga: "\u304C", gi: "\u304E", gu: "\u3050", ge: "\u3052", go: "\u3054", sa: "\u3055", shi: "\u3057", su: "\u3059", se: "\u305B", so: "\u305D", za: "\u3056", ji: "\u3058", zu: "\u305A", ze: "\u305C", zo: "\u305E", ta: "\u305F", chi: "\u3061", tsu: "\u3064", te: "\u3066", to: "\u3068", da: "\u3060", di: "\u3062", du: "\u3065", de: "\u3067", do: "\u3069", na: "\u306A", ni: "\u306B", nu: "\u306C", ne: "\u306D", no: "\u306E", ha: "\u306F", hi: "\u3072", fu: "\u3075", he: "\u3078", ho: "\u307B", ba: "\u3070", bi: "\u3073", bu: "\u3076", be: "\u3079", bo: "\u307C", pa: "\u3071", pi: "\u3074", pu: "\u3077", pe: "\u307A", po: "\u307D", ma: "\u307E", mi: "\u307F", mu: "\u3080", me: "\u3081", mo: "\u3082", ya: "\u3084", yu: "\u3086", yo: "\u3088", ra: "\u3089", ri: "\u308A", ru: "\u308B", re: "\u308C", ro: "\u308D", wa: "\u308F", wi: "\u3090", we: "\u3091", wo: "\u3092", n: "\u3093", kya: "\u304D\u3083", kyu: "\u304D\u3085", kyo: "\u304D\u3087", gya: "\u304E\u3083", gyu: "\u304E\u3085", gyo: "\u304E\u3087", sha: "\u3057\u3083", shu: "\u3057\u3085", sho: "\u3057\u3087", ja: "\u3058\u3083", ju: "\u3058\u3085", jo: "\u3058\u3087", cha: "\u3061\u3083", chu: "\u3061\u3085", cho: "\u3061\u3087", nya: "\u306B\u3083", nyu: "\u306B\u3085", nyo: "\u306B\u3087", hya: "\u3072\u3083", hyu: "\u3072\u3085", hyo: "\u3072\u3087", bya: "\u3073\u3083", byu: "\u3073\u3085", byo: "\u3073\u3087", pya: "\u3074\u3083", pyu: "\u3074\u3085", pyo: "\u3074\u3087", mya: "\u307F\u3083", myu: "\u307F\u3085", myo: "\u307F\u3087", rya: "\u308A\u3083", ryu: "\u308A\u3085", ryo: "\u308A\u3087", si: "\u3057", ti: "\u3061", tu: "\u3064", zi: "\u3058", nn: "\u3093", xya: "\u3083", xyu: "\u3085", xyo: "\u3087", xa: "\u3041", xi: "\u3043", xu: "\u3045", xe: "\u3047", xo: "\u3049", xtu: "\u3063", xtsu: "\u3063", ltu: "\u3063", ltsu: "\u3063", xwa: "\u308E", xke: "\u3096", xka: "\u3095" },
                s = d => {
                    let _ = "",
                        v = 0;
                    for (; v < d.length;) {
                        let M = !1;
                        for (let T = 4; T >= 1; T--)
                            if (v + T <= d.length) { const Oe = d.substring(v, v + T).toLowerCase(); if (n[Oe]) { _ += n[Oe], v += T, M = !0; break } }
                        M || (_ += d[v], v++)
                    }
                    return _
                },
                i = (d, _) => {
                    const v = _.selection.getBookmark(),
                        M = s(d);
                    M !== d ? (r.value = M, _.setContent(M), setTimeout(() => { _.selection.moveToBookmark(v) }, 10)) : r.value = d
                },
                u = {
                    height: 300,
                    menubar: !1,
                    plugins: ["advlist", "autolink", "lists", "link", "image", "charmap", "anchor", "searchreplace", "visualblocks", "code", "fullscreen", "insertdatetime", "media", "table", "preview", "help", "wordcount"],
                    toolbar: "undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help",
                    content_style: "body { font-family: Arial, sans-serif; font-size: 14px; }",
                    setup: d => {
                        d.on("input", _ => {
                            const v = d.getContent({ format: "text" });
                            i(v, d)
                        }), d.on("paste", _ => {
                            setTimeout(() => {
                                const v = d.getContent({ format: "text" });
                                i(v, d)
                            }, 10)
                        })
                    }
                },
                c = I(0),
                o = I(e.duration.duration),
                f = I([]),
                r = I("");
            Mt(() => {
                if (e.question_active)
                    if (e.question_active.question.question_type === "multiple") try {
                        const d = JSON.parse(e.question_active.selected_answers || "[]");
                        f.value = Array.isArray(d) ? d : []
                    } catch (d) { f.value = [], console.error("Error parsing selected answers:", d) } else e.question_active.question.question_type === "essay" && (r.value = e.question_active.essay_answer || "")
            }), jt(() => e.question_active, d => {
                if (d && d.question.question_type === "essay") r.value = d.essay_answer || "";
                else if (d && d.question.question_type === "multiple") try {
                    const _ = JSON.parse(d.selected_answers || "[]");
                    f.value = Array.isArray(_) ? _ : []
                } catch { f.value = [] }
            });
            const h = d => {
                    switch (d) {
                        case "single":
                            return "Pilihan Tunggal";
                        case "multiple":
                            return "Pilihan Ganda (Multiple)";
                        case "essay":
                            return "Essay";
                        default:
                            return "Pilihan Tunggal"
                    }
                },
                l = d => {
                    switch (d) {
                        case "single":
                            return "bg-info";
                        case "multiple":
                            return "bg-purple";
                        case "essay":
                            return "bg-warning";
                        default:
                            return "bg-info"
                    }
                },
                q = d => {
                    switch (d) {
                        case "single":
                            return "btn-info";
                        case "multiple":
                            return "btn-purple";
                        case "essay":
                            return "btn-warning";
                        default:
                            return "btn-info"
                    }
                },
                w = d => f.value.includes(d),
                C = d => {
                    const _ = f.value.indexOf(d);
                    _ === -1 ? f.value.push(d) : f.value.splice(_, 1)
                },
                N = (d, _) => { B.Inertia.post("/student/exam-answer", { exam_id: d, exam_session_id: e.exam_group.exam_session.id, question_id: _, selected_answers: JSON.stringify(f.value), duration: o.value }, { preserveScroll: !0, onSuccess: () => { j.fire({ position: "top-end", icon: "success", title: "Jawaban disimpan!", showConfirmButton: !1, timer: 1e3, toast: !0 }) } }) },
                L = (d, _) => { j.fire({ position: "top-end", icon: "info", title: "Menyimpan jawaban...", showConfirmButton: !1, toast: !0 }), B.Inertia.post("/student/exam-answer", { exam_id: d, exam_session_id: e.exam_group.exam_session.id, question_id: _, essay_answer: r.value, duration: o.value }, { preserveScroll: !0, onSuccess: () => { j.fire({ position: "top-end", icon: "success", title: "Jawaban essay disimpan!", showConfirmButton: !1, timer: 1e3, toast: !0 }) }, onError: v => { j.fire({ position: "top-end", icon: "error", title: "Gagal menyimpan jawaban!", text: Object.values(v).flat().join(" "), showConfirmButton: !1, timer: 3e3, toast: !0 }) } }) },
                P = d => {
                    if (d.question.question_type === "single") return d.answer !== 0;
                    if (d.question.question_type === "multiple") try { const _ = JSON.parse(d.selected_answers || "[]"); return Array.isArray(_) && _.length > 0 } catch { return !1 } else if (d.question.question_type === "essay") return d.essay_answer && d.essay_answer.trim() !== "";
                    return !1
                },
                R = () => { o.value = o.value - 1e3, c.value = c.value + 1, o.value > 0 && c.value % 10 == 1 && W.put(`/student/exam-duration/update/${e.duration.id}`, { duration: o.value }) },
                J = () => { W.put(`/student/exam-duration/update/${e.duration.id}`, { duration: o.value }), B.Inertia.get(`/student/exam/${e.id}/${e.page-1}`) },
                m = () => { W.put(`/student/exam-duration/update/${e.duration.id}`, { duration: o.value }), B.Inertia.get(`/student/exam/${e.id}/${e.page+1}`) },
                se = d => { W.put(`/student/exam-duration/update/${e.duration.id}`, { duration: o.value }), B.Inertia.get(`/student/exam/${e.id}/${d+1}`) },
                ie = (d, _, v) => { B.Inertia.post("/student/exam-answer", { exam_id: d, exam_session_id: e.exam_group.exam_session.id, question_id: _, answer: v, duration: o.value }, { preserveScroll: !0, onSuccess: () => { j.fire({ position: "top-end", icon: "success", title: "Jawaban disimpan!", showConfirmButton: !1, timer: 1e3, toast: !0 }) } }) },
                z = I(!1),
                V = I(!1);
            return { options: t, duration: o, handleChangeDuration: R, prevPage: J, nextPage: m, clickQuestion: se, submitAnswer: ie, showModalEndExam: z, showModalEndTimeExam: V, endExam: () => { j.fire({ title: "Memproses...", text: "Sedang menyimpan hasil ujian", allowOutsideClick: !1, didOpen: () => { j.showLoading() } }), B.Inertia.post("/student/exam-end", { exam_group_id: e.exam_group.id, exam_id: e.exam_group.exam.id, exam_session_id: e.exam_group.exam_session.id }, { onSuccess: () => { j.fire({ title: "Berhasil!", text: "Ujian Selesai! Mengalihkan ke halaman hasil...", icon: "success", showConfirmButton: !1, timer: 2e3 }) } }) }, selectedAnswers: f, isAnswerSelected: w, toggleMultipleAnswer: C, submitMultipleAnswers: N, questionIsAnswered: P, essayAnswer: r, submitEssayAnswer: L, getQuestionTypeLabel: h, getQuestionTypeBadgeClass: l, getQuestionTypeButtonClass: q, handleTinyMCEInput: i, tinymceConfig: u, convertToHiragana: s }
        }
    },
    An = { class: "container-fluid pb-5" },
    Tn = { class: "row mb-4" },
    Rn = { class: "col-md-8 mb-4 mb-md-0" },
    Mn = { class: "card border-0 shadow" },
    jn = { class: "card-header bg-white py-3" },
    On = { class: "d-flex justify-content-between align-items-center" },
    Nn = { class: "mb-0" },
    Ln = y("Soal No. "),
    Pn = { class: "fw-bold" },
    Bn = { class: "badge bg-info p-2 fs-6" },
    In = a("i", { class: "fa fa-clock me-1" }, null, -1),
    Un = { class: "card-body", style: { "min-height": "400px" } },
    Dn = { key: 0 },
    Hn = { class: "mb-2" },
    Fn = { key: 0, class: "badge bg-secondary ms-2" },
    Jn = { class: "question-text mb-4 pb-2 border-bottom" },
    zn = { key: 0, class: "question-media mb-3" },
    Vn = ["src"],
    Qn = { key: 1, class: "audio-player border rounded p-3 bg-light mb-3" },
    Wn = a("p", { class: "mb-2" }, [a("i", { class: "fa fa-music me-2" }), y(" File Audio Soal:")], -1),
    Xn = { controls: "", class: "w-100" },
    Kn = ["src"],
    Gn = y(" Browser Anda tidak mendukung pemutaran audio. "),
    Yn = ["innerHTML"],
    Zn = { key: 1, class: "alert alert-info" },
    $n = a("i", { class: "fa fa-info-circle me-2" }, null, -1),
    es = y(" Pilih semua jawaban yang benar "),
    ts = [$n, es],
    ns = { key: 2, class: "alert alert-warning" },
    ss = a("i", { class: "fa fa-pencil-alt me-2" }, null, -1),
    is = y(" Ketik jawaban essay Anda pada kotak di bawah "),
    as = [ss, is],
    rs = { key: 0, class: "answer-options" },
    os = { class: "d-flex" },
    us = { class: "option-label me-3" },
    ls = ["onClick"],
    ds = ["onClick"],
    cs = ["innerHTML"],
    fs = { key: 1, class: "answer-options" },
    hs = { class: "d-flex" },
    ms = { class: "option-label me-3" },
    _s = { class: "form-check" },
    vs = ["id", "checked", "onChange"],
    ps = ["for"],
    bs = { class: "ms-2 fs-5 fw-bold" },
    ys = ["onClick"],
    gs = ["innerHTML"],
    ws = { class: "d-flex justify-content-end mt-4" },
    xs = a("i", { class: "fa fa-save me-2" }, null, -1),
    qs = y(" Simpan Jawaban "),
    Es = [xs, qs],
    ks = { key: 2, class: "essay-answer mt-4" },
    Cs = { class: "form-group" },
    Ss = a("label", { for: "essay-answer", class: "form-label fw-bold mb-2" }, [a("i", { class: "fa fa-pen me-2" }), y("Jawaban Essay: ")], -1),
    As = { class: "d-flex justify-content-between align-items-center mt-2" },
    Ts = { class: "character-count text-muted" },
    Rs = a("div", { class: "romaji-indicator" }, [a("small", { class: "text-info" }, [a("i", { class: "fa fa-language me-1" }), y(" Romaji otomatis dikonversi ke Hiragana ")])], -1),
    Ms = { class: "d-flex justify-content-end mt-4" },
    js = a("i", { class: "fa fa-save me-2" }, null, -1),
    Os = y(" Simpan Jawaban "),
    Ns = [js, Os],
    Ls = { key: 1, class: "alert alert-danger border-0 shadow text-center" },
    Ps = a("i", { class: "fa fa-exclamation-triangle me-2" }, null, -1),
    Bs = y(" Soal Tidak Ditemukan! "),
    Is = [Ps, Bs],
    Us = { class: "card-footer bg-white py-3" },
    Ds = { class: "d-flex justify-content-between" },
    Hs = a("i", { class: "fa fa-arrow-left me-2" }, null, -1),
    Fs = y(" Sebelumnya "),
    Js = [Hs, Fs],
    zs = y(" Selanjutnya "),
    Vs = a("i", { class: "fa fa-arrow-right ms-2" }, null, -1),
    Qs = [zs, Vs],
    Ws = { class: "col-md-4" },
    Xs = { class: "card border-0 shadow mb-4" },
    Ks = { class: "card-header bg-white py-3" },
    Gs = { class: "d-flex justify-content-between align-items-center" },
    Ys = a("h5", { class: "mb-0" }, "Navigasi Soal", -1),
    Zs = { class: "badge bg-success p-2" },
    $s = a("i", { class: "fa fa-check-circle me-1" }, null, -1),
    ei = { class: "card-body question-nav", style: { height: "320px", "overflow-y": "auto" } },
    ti = { class: "row g-2" },
    ni = ["onClick", "title"],
    si = { key: 0, class: "small" },
    ii = a("i", { class: "fa fa-pen-alt" }, null, -1),
    ai = [ii],
    ri = { class: "card-footer bg-white py-3" },
    oi = a("i", { class: "fa fa-flag-checkered me-2" }, null, -1),
    ui = y(" Akhiri Ujian "),
    li = [oi, ui],
    di = { class: "card border-0 shadow" },
    ci = a("div", { class: "card-header bg-white py-3" }, [a("h5", { class: "mb-0" }, [a("i", { class: "fa fa-info-circle me-2" }), y(" Informasi Ujian")])], -1),
    fi = { class: "card-body" },
    hi = { class: "list-group list-group-flush" },
    mi = { class: "list-group-item d-flex justify-content-between align-items-center px-0" },
    _i = a("span", null, "Materi:", -1),
    vi = { class: "fw-bold" },
    pi = { class: "list-group-item d-flex justify-content-between align-items-center px-0" },
    bi = a("span", null, "Total Soal:", -1),
    yi = { class: "fw-bold" },
    gi = { class: "list-group-item d-flex justify-content-between align-items-center px-0" },
    wi = a("span", null, "Sudah Dijawab:", -1),
    xi = { class: "fw-bold" },
    qi = { class: "list-group-item d-flex justify-content-between align-items-center px-0" },
    Ei = a("span", null, "Belum Dijawab:", -1),
    ki = { class: "fw-bold" },
    Ci = { key: 0, class: "modal fade show", tabindex: "-1", "aria-hidden": "true", style: { display: "block", "z-index": "1050" }, role: "dialog" },
    Si = { class: "modal-dialog modal-dialog-centered", style: { "z-index": "1055" } },
    Ai = { class: "modal-content border-0 shadow" },
    Ti = a("div", { class: "modal-header bg-danger text-white" }, [a("h5", { class: "modal-title" }, [a("i", { class: "fa fa-exclamation-triangle me-2" }), y(" Konfirmasi Akhiri Ujian")])], -1),
    Ri = { class: "modal-body py-4" },
    Mi = a("p", { class: "fs-5 mb-0" }, "Setelah mengakhiri ujian, Anda tidak dapat kembali ke ujian ini lagi.", -1),
    ji = { key: 0, class: "alert alert-warning mt-3" },
    Oi = a("i", { class: "fa fa-exclamation-circle me-2" }, null, -1),
    Ni = y(" Masih ada "),
    Li = y(" soal yang belum Anda jawab. "),
    Pi = a("p", { class: "fw-bold mt-3 mb-0" }, "Yakin akan mengakhiri ujian?", -1),
    Bi = { class: "modal-footer" },
    Ii = a("i", { class: "fa fa-check me-2" }, null, -1),
    Ui = y(" Ya, Akhiri Ujian "),
    Di = [Ii, Ui],
    Hi = a("i", { class: "fa fa-times me-2" }, null, -1),
    Fi = y(" Kembali ke Ujian "),
    Ji = [Hi, Fi],
    zi = a("div", { class: "modal-backdrop fade show", style: { "z-index": "1040" } }, null, -1),
    Vi = { key: 1, class: "modal fade show", "data-bs-backdrop": "static", "data-bs-keyboard": "false", tabindex: "-1", "aria-hidden": "true", style: { display: "block", "z-index": "1050" }, role: "dialog" },
    Qi = { class: "modal-dialog modal-dialog-centered", style: { "z-index": "1055" } },
    Wi = { class: "modal-content border-0 shadow" },
    Xi = Ot('<div class="modal-header bg-warning"><h5 class="modal-title"><i class="fa fa-clock me-2"></i> Waktu Habis!</h5></div><div class="modal-body py-4 text-center"><i class="fa fa-stopwatch fa-4x mb-3 text-warning"></i><p class="fs-5">Waktu ujian sudah berakhir!</p><p>Klik <strong class="fw-bold">Selesai</strong> untuk mengakhiri ujian.</p></div>', 2),
    Ki = { class: "modal-footer" },
    Gi = a("i", { class: "fa fa-check-circle me-2" }, null, -1),
    Yi = y(" Selesai "),
    Zi = [Gi, Yi],
    $i = a("div", { class: "modal-backdrop fade show", style: { "z-index": "1040" } }, null, -1);

function ea(e, t, n, s, i, u) {
    const c = re("Head"),
        o = re("VueCountdown"),
        f = re("Editor");
    return p(), b(Q, null, [oe(c, null, { default: Ne(() => [a("title", null, "Ujian: Soal No. " + g(n.page) + " - Aplikasi Ujian Online", 1)]), _: 1 }), a("div", An, [a("div", Tn, [a("div", Rn, [a("div", Mn, [a("div", jn, [a("div", On, [a("h5", Nn, [Ln, a("strong", Pn, g(n.page), 1), y(" dari " + g(n.all_questions.length), 1)]), a("div", null, [oe(o, { time: s.duration, onProgress: s.handleChangeDuration, onEnd: t[0] || (t[0] = r => s.showModalEndTimeExam = !0) }, { default: Ne(({ hours: r, minutes: h, seconds: l }) => [a("span", Bn, [In, y(" " + g(r) + " jam, " + g(h) + " menit, " + g(l) + " detik ", 1)])]), _: 1 }, 8, ["time", "onProgress"])])])]), a("div", Un, [n.question_active !== null ? (p(), b("div", Dn, [a("div", Hn, [a("span", { class: H(["badge", s.getQuestionTypeBadgeClass(n.question_active.question.question_type)]) }, g(s.getQuestionTypeLabel(n.question_active.question.question_type)), 3), n.question_active.question.media_type !== "none" ? (p(), b("span", Fn, g(n.question_active.question.media_type === "image" ? "Dengan Gambar" : "Dengan Audio"), 1)) : E("", !0)]), a("div", Jn, [n.question_active.question.media_type !== "none" ? (p(), b("div", zn, [n.question_active.question.media_type === "image" && n.question_active.question.question_image ? (p(), b("img", { key: 0, src: `/storage/questions/${n.question_active.question.question_image}`, class: "img-fluid rounded mb-3 border shadow-sm", alt: "Question Image", style: { "max-height": "300px" } }, null, 8, Vn)) : E("", !0), n.question_active.question.media_type === "audio" && n.question_active.question.audio_file ? (p(), b("div", Qn, [Wn, a("audio", Xn, [a("source", { src: `/storage/questions/${n.question_active.question.audio_file}`, type: "audio/mpeg" }, null, 8, Kn), Gn])])) : E("", !0)])) : E("", !0), a("p", { innerHTML: n.question_active.question.question, class: "fs-5" }, null, 8, Yn), n.question_active.question.question_type === "multiple" ? (p(), b("div", Zn, ts)) : E("", !0), n.question_active.question.question_type === "essay" ? (p(), b("div", ns, as)) : E("", !0)]), n.question_active.question.question_type === "single" ? (p(), b("div", rs, [(p(!0), b(Q, null, ue(n.answer_order, (r, h) => (p(), b("div", { key: h, class: "answer-option mb-3" }, [a("div", os, [a("div", us, [a("button", { class: H(["btn btn-lg fw-bold", r == n.question_active.answer ? "btn-info" : "btn-outline-info"]), onClick: S(l => r != n.question_active.answer && s.submitAnswer(n.question_active.question.exam.id, n.question_active.question.id, r), ["prevent"]) }, g(s.options[h]), 11, ls)]), a("div", { class: H(["option-content flex-grow-1", { selected: r == n.question_active.answer }]), onClick: S(l => r != n.question_active.answer && s.submitAnswer(n.question_active.question.exam.id, n.question_active.question.id, r), ["prevent"]) }, [a("p", { innerHTML: n.question_active.question["option_" + r], class: "mb-0" }, null, 8, cs)], 10, ds)])]))), 128))])) : n.question_active.question.question_type === "multiple" ? (p(), b("div", fs, [(p(!0), b(Q, null, ue(n.answer_order, (r, h) => (p(), b("div", { key: h, class: "answer-option mb-3" }, [a("div", hs, [a("div", ms, [a("div", _s, [a("input", { class: "form-check-input", type: "checkbox", id: "option-" + r, checked: s.isAnswerSelected(r), onChange: l => s.toggleMultipleAnswer(r), style: { width: "25px", height: "25px", cursor: "pointer" } }, null, 40, vs), a("label", { class: "form-check-label", for: "option-" + r }, [a("span", bs, g(s.options[h]), 1)], 8, ps)])]), a("div", { class: H(["option-content flex-grow-1", { selected: s.isAnswerSelected(r) }]), onClick: S(l => s.toggleMultipleAnswer(r), ["prevent"]) }, [a("p", { innerHTML: n.question_active.question["option_" + r], class: "mb-0" }, null, 8, gs)], 10, ys)])]))), 128)), a("div", ws, [a("button", { onClick: t[1] || (t[1] = S(r => s.submitMultipleAnswers(n.question_active.question.exam.id, n.question_active.question.id), ["prevent"])), class: "btn btn-primary px-4" }, Es)])])) : n.question_active.question.question_type === "essay" ? (p(), b("div", ks, [a("div", Cs, [Ss, oe(f, { "api-key": "f4g16s2kaw96ta82x5udni28fxmdk833fkdpwdduyrzb20gr", modelValue: s.essayAnswer, "onUpdate:modelValue": t[2] || (t[2] = r => s.essayAnswer = r), init: s.tinymceConfig, onInput: s.handleTinyMCEInput }, null, 8, ["modelValue", "init", "onInput"]), a("div", As, [a("div", Ts, [a("small", null, g(s.essayAnswer.length) + " karakter", 1)]), Rs]), a("div", Ms, [a("button", { onClick: t[3] || (t[3] = S(r => s.submitEssayAnswer(n.question_active.question.exam.id, n.question_active.question.id), ["prevent"])), class: "btn btn-primary px-4" }, Ns)])])])) : E("", !0)])) : (p(), b("div", Ls, Is))]), a("div", Us, [a("div", Ds, [a("div", null, [n.page > 1 ? (p(), b("button", { key: 0, onClick: t[4] || (t[4] = S((...r) => s.prevPage && s.prevPage(...r), ["prevent"])), class: "btn btn-secondary px-4" }, Js)) : E("", !0)]), a("div", null, [n.page < n.all_questions.length ? (p(), b("button", { key: 0, onClick: t[5] || (t[5] = S((...r) => s.nextPage && s.nextPage(...r), ["prevent"])), class: "btn btn-secondary px-4" }, Qs)) : E("", !0)])])])])]), a("div", Ws, [a("div", Xs, [a("div", Ks, [a("div", Gs, [Ys, a("div", Zs, [$s, y(" " + g(n.question_answered) + " dari " + g(n.all_questions.length) + " Dikerjakan ", 1)])])]), a("div", ei, [a("div", ti, [(p(!0), b(Q, null, ue(n.all_questions, (r, h) => (p(), b("div", { key: h, class: "col-2" }, [a("button", { onClick: S(l => s.clickQuestion(h), ["prevent"]), class: H(["btn btn-sm w-100 question-button", h + 1 == n.page ? "btn-dark" : s.questionIsAnswered(r) ? s.getQuestionTypeButtonClass(r.question.question_type) : "btn-outline-info"]), title: s.getQuestionTypeLabel(r.question.question_type) }, [y(g(h + 1) + " ", 1), r.question.question_type === "essay" ? (p(), b("span", si, ai)) : E("", !0)], 10, ni)]))), 128))])]), a("div", ri, [a("button", { onClick: t[6] || (t[6] = r => s.showModalEndExam = !0), class: "btn btn-danger btn-lg w-100 border-0 shadow" }, li)])]), a("div", di, [ci, a("div", fi, [a("ul", hi, [a("li", mi, [_i, a("span", vi, g(n.exam_group.exam.lesson.title), 1)]), a("li", pi, [bi, a("span", yi, g(n.all_questions.length), 1)]), a("li", gi, [wi, a("span", xi, g(n.question_answered), 1)]), a("li", qi, [Ei, a("span", ki, g(n.all_questions.length - n.question_answered), 1)])])])])])])]), s.showModalEndExam ? (p(), b("div", Ci, [a("div", Si, [a("div", Ai, [Ti, a("div", Ri, [Mi, n.all_questions.length - n.question_answered > 0 ? (p(), b("div", ji, [Oi, Ni, a("strong", null, g(n.all_questions.length - n.question_answered), 1), Li])) : E("", !0), Pi]), a("div", Bi, [a("button", { onClick: t[7] || (t[7] = S((...r) => s.endExam && s.endExam(...r), ["prevent"])), type: "button", class: "btn btn-danger px-4" }, Di), a("button", { onClick: t[8] || (t[8] = S(r => s.showModalEndExam = !1, ["prevent"])), type: "button", class: "btn btn-secondary px-4" }, Ji)])])]), zi])) : E("", !0), s.showModalEndTimeExam ? (p(), b("div", Vi, [a("div", Qi, [a("div", Wi, [Xi, a("div", Ki, [a("button", { onClick: t[9] || (t[9] = S((...r) => s.endExam && s.endExam(...r), ["prevent"])), type: "button", class: "btn btn-primary w-100" }, Zi)])])]), $i])) : E("", !0)], 64)
}
const ra = Nt(Sn, [
    ["render", ea]
]);
export { ra as default };