import { L as r } from "./Student.db2eab57.js";
import { H as u, L as h, a as c, o as a, c as n, b as d, w as l, d as t, t as e, F as m, i as o } from "./app.e795c71f.js";
import { _ as b } from "./_plugin-vue_export-helper.cdc0426e.js";
const f = { layout: r, components: { Head: u, Link: h }, props: { exam_group: Object, grade: Object } },
    w = t("title", null, "Konfirmasi Ujian - Aplikasi Ujian Online", -1),
    x = { class: "row" },
    p = { class: "col-md-12" },
    g = t("i", { class: "fa fa-long-arrow-alt-left me-2" }, null, -1),
    v = o(" Kembali"),
    k = { class: "row" },
    y = { class: "col-md-6" },
    L = { class: "card border-0 shadow" },
    j = { class: "card-body" },
    H = t("h5", null, [t("i", { class: "fa fa-file" }), o(" Deskripsi Ujian")], -1),
    M = t("hr", null, null, -1),
    N = ["innerHTML"],
    D = { class: "col-md-6" },
    U = { class: "card border-0 shadow" },
    B = { class: "card-body" },
    C = t("h5", null, [t("i", { class: "fa fa-list-ul" }), o(" Detail Peserta")], -1),
    K = t("hr", null, null, -1),
    O = { class: "table-responsive" },
    S = { class: "table table-centered table-nowrap mb-0 rounded" },
    T = t("td", { class: "fw-bold" }, "Nisn", -1),
    V = t("td", { class: "fw-bold" }, "Nama Lengkap", -1),
    F = t("td", { class: "fw-bold" }, "Kelas", -1),
    P = t("td", { class: "fw-bold" }, "Ujian", -1),
    A = t("td", { class: "fw-bold" }, "Materi", -1),
    E = t("td", { class: "fw-bold" }, "Durasi", -1),
    q = { key: 0 },
    z = o("Mulai"),
    G = { key: 1 },
    I = t("button", { class: "btn btn-md btn-primary border-0 shadow w-100 mt-2", disabled: "" }, "Sudah Mengerjakan", -1),
    J = [I];

function Q(R, W, s, X, Y, Z) { const _ = c("Head"),
        i = c("Link"); return a(), n(m, null, [d(_, null, { default: l(() => [w]), _: 1 }), t("div", x, [t("div", p, [d(i, { href: "/student/dashboard", class: "btn btn-md btn-primary border-0 shadow mb-3", type: "button" }, { default: l(() => [g, v]), _: 1 })])]), t("div", k, [t("div", y, [t("div", L, [t("div", j, [H, M, t("div", { innerHTML: s.exam_group.exam.description }, null, 8, N)])])]), t("div", D, [t("div", U, [t("div", B, [C, K, t("div", O, [t("table", S, [t("thead", null, [t("tr", null, [T, t("td", null, e(s.exam_group.student.nisn), 1)]), t("tr", null, [V, t("td", null, e(s.exam_group.student.name), 1)]), t("tr", null, [F, t("td", null, e(s.exam_group.student.classroom.title), 1)]), t("tr", null, [P, t("td", null, e(s.exam_group.exam.title), 1)]), t("tr", null, [A, t("td", null, e(s.exam_group.exam.lesson.title), 1)]), t("tr", null, [E, t("td", null, e(s.exam_group.exam.duration) + " Menit", 1)])])])]), s.grade.end_time == null ? (a(), n("div", q, [d(i, { href: `/student/exam-start/${s.exam_group.id}`, class: "btn btn-md btn-success border-0 shadow w-100 mt-2 text-white" }, { default: l(() => [z]), _: 1 }, 8, ["href"])])) : (a(), n("div", G, J))])])])])], 64) }
const et = b(f, [
    ["render", Q]
]);
export { et as default };