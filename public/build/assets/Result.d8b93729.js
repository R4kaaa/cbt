import { L as o } from "./Student.db2eab57.js";
import { H as d, L as c, a as _, o as a, c as l, b as i, w as r, d as t, t as s, g as u, F as m, i as h } from "./app.e795c71f.js";
import { _ as b } from "./_plugin-vue_export-helper.cdc0426e.js";
const f = { layout: o, components: { Head: d, Link: c }, props: { exam_group: Object, grade: Object } },
    g = t("title", null, "Hasil Ujian - Aplikasi Ujian Online", -1),
    w = { class: "row justify-content-center mb-5" },
    x = { class: "col-md-8" },
    j = { class: "card border-0 shadow" },
    k = { class: "card-body" },
    p = t("h5", null, [t("i", { class: "fa fa-check-circle" }), h(" Ujian Selesai")], -1),
    v = t("hr", null, null, -1),
    y = { class: "table-responsive" },
    N = { class: "table table-centered table-nowrap mb-0 rounded" },
    H = t("td", { class: "fw-bold" }, "Nisn", -1),
    L = t("td", { class: "fw-bold" }, "Nama Lengkap", -1),
    B = t("td", { class: "fw-bold" }, "Kelas", -1),
    M = t("td", { class: "fw-bold" }, "Ujian", -1),
    S = t("td", { class: "fw-bold" }, "Materi", -1),
    U = t("td", { class: "fw-bold" }, "Mulai Mengerjakan", -1),
    V = t("td", { class: "fw-bold" }, "Selesai Mengerjakan", -1),
    C = { key: 0 },
    O = t("td", { class: "fw-bold" }, "Jumlah Benar", -1),
    F = t("td", { class: "fw-bold" }, "Nilai", -1);

function A(D, E, e, J, K, P) { const n = _("Head"); return a(), l(m, null, [i(n, null, { default: r(() => [g]), _: 1 }), t("div", w, [t("div", x, [t("div", j, [t("div", k, [p, v, t("div", y, [t("table", N, [t("thead", null, [t("tr", null, [H, t("td", null, s(e.exam_group.student.nisn), 1)]), t("tr", null, [L, t("td", null, s(e.exam_group.student.name), 1)]), t("tr", null, [B, t("td", null, s(e.exam_group.student.classroom.title), 1)]), t("tr", null, [M, t("td", null, s(e.exam_group.exam.title), 1)]), t("tr", null, [S, t("td", null, s(e.exam_group.exam.lesson.title), 1)]), t("tr", null, [U, t("td", null, s(e.grade.start_time), 1)]), t("tr", null, [V, t("td", null, s(e.grade.end_time), 1)])]), e.exam_group.exam.show_answer == "Y" ? (a(), l("tbody", C, [t("tr", null, [O, t("td", null, s(e.grade.total_correct), 1)]), t("tr", null, [F, t("td", null, s(e.grade.grade), 1)])])) : u("", !0)])])])])])])], 64) }
const q = b(f, [
    ["render", A]
]);
export { q as default };