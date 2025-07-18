import { L as x } from "./Student.db2eab57.js";
import { L as p, a as c, o as t, c as o, b as l, w as _, d as e, t as n, F as m, p as g, i as a, g as u } from "./app.e795c71f.js";
import { _ as w } from "./_plugin-vue_export-helper.cdc0426e.js";
const k = { layout: x, components: { Link: p }, props: { exam_groups: Array, auth: Object } },
    f = e("title", null, "Dashboard Siswa - Aplikasi Ujian Online", -1),
    v = { class: "row" },
    y = { class: "col-md-12" },
    j = { class: "alert alert-success border-0 shadow" },
    L = a(" Selamat Datang "),
    S = { key: 0, class: "row" },
    C = { class: "card border-0 shadow" },
    T = { class: "card-body" },
    B = e("hr", null, null, -1),
    D = { class: "table-responsive" },
    N = { class: "table table-centered table-nowrap mb-0 rounded" },
    V = e("td", { class: "fw-bold" }, "Materi", -1),
    K = e("td", { class: "fw-bold" }, "Kelas", -1),
    M = e("td", { class: "fw-bold" }, "Sesi", -1),
    $ = e("td", { class: "fw-bold" }, "Mulai", -1),
    A = e("td", { class: "fw-bold" }, "Selesai", -1),
    E = { key: 0 },
    F = { key: 0 },
    H = { key: 0 },
    O = a("Kerjakan"),
    I = { key: 1 },
    P = a("Lanjut Kerjakan"),
    R = { key: 1 },
    U = { key: 0 },
    W = e("button", { class: "btn btn-md btn-gray-700 border-0 shadow w-100 mt-2", disabled: "" }, "Belum Mulai", -1),
    q = [W],
    z = { key: 1 },
    G = e("button", { class: "btn btn-md btn-danger border-0 shadow w-100 mt-2", disabled: "" }, "Waktu Terlewat", -1),
    J = [G],
    Q = { key: 1 },
    X = e("button", { class: "btn btn-md btn-danger border-0 shadow w-100 mt-2", disabled: "" }, "Sudah Dikerjakan", -1),
    Y = [X],
    Z = { key: 1, class: "row" },
    ee = e("div", { class: "col-md-12" }, [e("div", { class: "alert alert-danger border-0 shadow" }, [e("i", { class: "fa fa-info-circle" }), a(" Tidak ada ujian yang tersedia ")])], -1),
    se = [ee];

function te(d, oe, i, ne, ae, de) { const h = c("Head"),
        r = c("Link"); return t(), o(m, null, [l(h, null, { default: _(() => [f]), _: 1 }), e("div", v, [e("div", y, [e("div", j, [L, e("strong", null, n(i.auth.student.name), 1)])])]), i.exam_groups.length > 0 ? (t(), o("div", S, [(t(!0), o(m, null, g(i.exam_groups, (s, b) => (t(), o("div", { class: "col-md-6", key: b }, [e("div", C, [e("div", T, [e("h5", null, n(s.exam_group.exam.title), 1), B, e("div", D, [e("table", N, [e("thead", null, [e("tr", null, [V, e("td", null, n(s.exam_group.exam.lesson.title), 1)]), e("tr", null, [K, e("td", null, n(s.exam_group.student.classroom.title), 1)]), e("tr", null, [M, e("td", null, n(s.exam_group.exam_session.title), 1)]), e("tr", null, [$, e("td", null, n(s.exam_group.exam_session.start_time), 1)]), e("tr", null, [A, e("td", null, n(s.exam_group.exam_session.end_time), 1)])])])]), s.grade.end_time == null ? (t(), o("div", E, [d.examTimeRangeChecker(s.exam_group.exam_session.start_time, s.exam_group.exam_session.end_time) ? (t(), o("div", F, [s.grade.start_time == null ? (t(), o("div", H, [l(r, { href: `/student/exam-confirmation/${s.exam_group.id}`, class: "btn btn-md btn-success border-0 shadow w-100 mt-2 text-white" }, { default: _(() => [O]), _: 2 }, 1032, ["href"])])) : (t(), o("div", I, [l(r, { href: `/student/exam/${s.exam_group.id}/1`, class: "btn btn-md btn-info border-0 shadow w-100 mt-2" }, { default: _(() => [P]), _: 2 }, 1032, ["href"])]))])) : (t(), o("div", R, [d.examTimeStartChecker(s.exam_group.exam_session.start_time) ? (t(), o("div", U, q)) : u("", !0), d.examTimeEndChecker(s.exam_group.exam_session.end_time) ? (t(), o("div", z, J)) : u("", !0)]))])) : (t(), o("div", Q, Y))])])]))), 128))])) : (t(), o("div", Z, se))], 64) }
const re = w(k, [
    ["render", te]
]);
export { re as default };