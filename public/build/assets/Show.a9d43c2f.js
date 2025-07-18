import { L as y } from "./Admin.3b832e96.js";
import { P as k } from "./Pagination.5eba1729.js";
import { H as L, L as v, a as r, o as _, c as h, b as n, w as o, d as t, t as a, F as b, p as H, h as M, i, n as l, e as T } from "./app.e795c71f.js";
import { S as f } from "./sweetalert2.all.0276e8f3.js";
import { _ as g } from "./_plugin-vue_export-helper.cdc0426e.js";
const C = { layout: y, components: { Head: L, Link: v, Pagination: k }, props: { errors: Object, exam: Object }, setup() { return { destroy: (u, e) => { f.fire({ title: "Apakah Anda yakin?", text: "Anda tidak akan dapat mengembalikan ini!", icon: "warning", showCancelButton: !0, confirmButtonColor: "#3085d6", cancelButtonColor: "#d33", confirmButtonText: "Yes, delete it!" }).then(c => { c.isConfirmed && (M.Inertia.delete(`/admin/exams/${u}/questions/${e}/destroy`), f.fire({ title: "Deleted!", text: "Soal Ujian Berhasil Dihapus!.", icon: "success", timer: 2e3, showConfirmButton: !1 })) }) } } } },
    j = t("title", null, "Detail Ujian - Aplikasi Ujian Online", -1),
    B = { class: "container-fluid mb-5 mt-5" },
    S = { class: "row" },
    A = { class: "col-md-12" },
    D = t("i", { class: "fa fa-long-arrow-alt-left me-2" }, null, -1),
    U = i(" Kembali"),
    N = { class: "card border-0 shadow mb-4" },
    P = { class: "card-body" },
    O = t("h5", null, [t("i", { class: "fa fa-edit" }), i(" Detail Ujian")], -1),
    V = t("hr", null, null, -1),
    F = { class: "table-responsive" },
    I = { class: "table table-bordered table-centered table-nowrap mb-0 rounded" },
    K = t("td", { style: { width: "30%" }, class: "fw-bold" }, "Nama Ujian", -1),
    z = t("td", { class: "fw-bold" }, "Materi", -1),
    E = t("td", { class: "fw-bold" }, "Kelas", -1),
    J = t("td", { class: "fw-bold" }, "Jumlah Soal", -1),
    Y = t("td", { class: "fw-bold" }, "Durasi (Menit)", -1),
    G = { class: "card border-0 shadow" },
    Q = { class: "card-body" },
    R = t("h5", null, [t("i", { class: "fa fa-question-circle" }), i(" Soal Ujian")], -1),
    W = t("hr", null, null, -1),
    X = t("i", { class: "fa fa-plus-circle" }, null, -1),
    Z = i(" Tambah"),
    q = t("i", { class: "fa fa-file-excel" }, null, -1),
    $ = i(" Import"),
    tt = { class: "table-responsive mt-3" },
    et = { class: "table table-bordered table-centered table-nowrap mb-0 rounded" },
    st = t("thead", { class: "thead-dark" }, [t("tr", { class: "border-0" }, [t("th", { class: "border-0 rounded-start", style: { width: "5%" } }, "No."), t("th", { class: "border-0" }, "Soal"), t("th", { class: "border-0 rounded-end", style: { width: "15%" } }, "Aksi")])], -1),
    nt = t("div", { class: "mt-2" }, null, -1),
    at = { class: "fw-bold text-center" },
    ot = ["innerHTML"],
    lt = t("hr", null, null, -1),
    it = { type: "A" },
    dt = ["innerHTML"],
    ct = ["innerHTML"],
    rt = ["innerHTML"],
    _t = ["innerHTML"],
    ht = ["innerHTML"],
    ut = { class: "text-center" },
    mt = t("i", { class: "fa fa-pencil-alt" }, null, -1),
    bt = ["onClick"],
    ft = t("i", { class: "fa fa-trash" }, null, -1),
    wt = [ft];

function xt(w, u, e, c, pt, yt) { const x = r("Head"),
        d = r("Link"),
        p = r("Pagination"); return _(), h(b, null, [n(x, null, { default: o(() => [j]), _: 1 }), t("div", B, [t("div", S, [t("div", A, [n(d, { href: "/admin/exams", class: "btn btn-md btn-primary border-0 shadow mb-3", type: "button" }, { default: o(() => [D, U]), _: 1 }), t("div", N, [t("div", P, [O, V, t("div", F, [t("table", I, [t("tbody", null, [t("tr", null, [K, t("td", null, a(e.exam.title), 1)]), t("tr", null, [z, t("td", null, a(e.exam.lesson.title), 1)]), t("tr", null, [E, t("td", null, a(e.exam.classroom.title), 1)]), t("tr", null, [J, t("td", null, a(e.exam.questions.data.length), 1)]), t("tr", null, [Y, t("td", null, a(e.exam.duration) + " Menit", 1)])])])])])]), t("div", G, [t("div", Q, [R, W, n(d, { href: `/admin/exams/${e.exam.id}/questions/create`, class: "btn btn-md btn-primary border-0 shadow me-2", type: "button" }, { default: o(() => [X, Z]), _: 1 }, 8, ["href"]), n(d, { href: `/admin/exams/${e.exam.id}/questions/import`, class: "btn btn-md btn-success border-0 shadow text-white", type: "button" }, { default: o(() => [q, $]), _: 1 }, 8, ["href"]), t("div", tt, [t("table", et, [st, nt, t("tbody", null, [(_(!0), h(b, null, H(e.exam.questions.data, (s, m) => (_(), h("tr", { key: m }, [t("td", at, a(++m + (e.exam.questions.current_page - 1) * e.exam.questions.per_page), 1), t("td", null, [t("div", { class: "fw-bold", innerHTML: s.question }, null, 8, ot), lt, t("ol", it, [t("li", { innerHTML: s.option_1, class: l({ "text-success fw-bold": s.answer == "1" }) }, null, 10, dt), t("li", { innerHTML: s.option_2, class: l({ "text-success fw-bold": s.answer == "2" }) }, null, 10, ct), t("li", { innerHTML: s.option_3, class: l({ "text-success fw-bold": s.answer == "3" }) }, null, 10, rt), t("li", { innerHTML: s.option_4, class: l({ "text-success fw-bold": s.answer == "4" }) }, null, 10, _t), t("li", { innerHTML: s.option_5, class: l({ "text-success fw-bold": s.answer == "5" }) }, null, 10, ht)])]), t("td", ut, [n(d, { href: `/admin/exams/${e.exam.id}/questions/${s.id}/edit`, class: "btn btn-sm btn-info border-0 shadow me-2", type: "button" }, { default: o(() => [mt]), _: 2 }, 1032, ["href"]), t("button", { onClick: T(kt => c.destroy(e.exam.id, s.id), ["prevent"]), class: "btn btn-sm btn-danger border-0" }, wt, 8, bt)])]))), 128))])])]), n(p, { links: e.exam.questions.links, align: "end" }, null, 8, ["links"])])])])])])], 64) }
const gt = g(C, [
    ["render", xt]
]);
export { gt as default };