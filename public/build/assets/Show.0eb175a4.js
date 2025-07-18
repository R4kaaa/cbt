import { L as p } from "./Admin.3b832e96.js";
import { P as y } from "./Pagination.5eba1729.js";
import { H as k, L as g, a as i, o as d, c as r, b as o, w as c, d as s, t as e, F as h, p as v, h as S, i as a, e as B } from "./app.e795c71f.js";
import { S as b } from "./sweetalert2.all.0276e8f3.js";
import { _ as C } from "./_plugin-vue_export-helper.cdc0426e.js";
const j = { layout: p, components: { Head: k, Link: g, Pagination: y }, props: { errors: Object, exam_session: Object }, setup() { return { destroy: (_, t) => { b.fire({ title: "Apakah Anda yakin?", text: "Anda tidak akan dapat mengembalikan ini!", icon: "warning", showCancelButton: !0, confirmButtonColor: "#3085d6", cancelButtonColor: "#d33", confirmButtonText: "Yes, delete it!" }).then(l => { l.isConfirmed && (S.Inertia.delete(`/admin/exam_sessions/${_}/enrolle/${t}/destroy`), b.fire({ title: "Deleted!", text: "Siswa Berhasil Dihapus!.", icon: "success", timer: 2e3, showConfirmButton: !1 })) }) } } } },
    L = s("title", null, "Detail Sesi Ujian - Aplikasi Ujian Online", -1),
    A = { class: "container-fluid mb-5 mt-5" },
    N = { class: "row" },
    D = { class: "col-md-12" },
    P = s("i", { class: "fa fa-long-arrow-alt-left me-2" }, null, -1),
    H = a(" Kembali"),
    K = { class: "card border-0 shadow mb-4" },
    U = { class: "card-body" },
    E = s("h5", null, [s("i", { class: "fa fa-stopwatch" }), a(" Detail Sesi Ujian")], -1),
    M = s("hr", null, null, -1),
    O = { class: "table-responsive" },
    V = { class: "table table-bordered table-centered table-nowrap mb-0 rounded" },
    F = s("td", { style: { width: "30%" }, class: "fw-bold" }, "Nama Ujian", -1),
    T = s("td", { class: "fw-bold" }, "Materi", -1),
    I = s("td", { class: "fw-bold" }, "Kelas", -1),
    J = s("td", { class: "fw-bold" }, "Sesi", -1),
    Y = s("td", { class: "fw-bold" }, "Mulai", -1),
    q = s("td", { class: "fw-bold" }, "Selesai", -1),
    z = { class: "card border-0 shadow" },
    G = { class: "card-body" },
    Q = s("h5", null, [s("i", { class: "fa fa-user-plus" }), a(" Enrolled Siswa")], -1),
    R = s("hr", null, null, -1),
    W = s("i", { class: "fa fa-user-plus" }, null, -1),
    X = a(" Enrolle Siswa"),
    Z = { class: "table-responsive mt-3" },
    $ = { class: "table table-bordered table-centered table-nowrap mb-0 rounded" },
    ss = s("thead", { class: "thead-dark" }, [s("tr", { class: "border-0" }, [s("th", { class: "border-0 rounded-start", style: { width: "5%" } }, "No."), s("th", { class: "border-0" }, "Nama Siswa"), s("th", { class: "border-0" }, "Kelas"), s("th", { class: "border-0" }, "Jenis Kelamin"), s("th", { class: "border-0 rounded-end", style: { width: "15%" } }, "Aksi")])], -1),
    ts = s("div", { class: "mt-2" }, null, -1),
    es = { class: "fw-bold text-center" },
    ns = { class: "text-center" },
    os = { class: "text-center" },
    as = { class: "text-center" },
    ls = ["onClick"],
    is = s("i", { class: "fa fa-trash" }, null, -1),
    ds = [is];

function rs(f, _, t, l, cs, _s) { const x = i("Head"),
        m = i("Link"),
        w = i("Pagination"); return d(), r(h, null, [o(x, null, { default: c(() => [L]), _: 1 }), s("div", A, [s("div", N, [s("div", D, [o(m, { href: "/admin/exam_sessions", class: "btn btn-md btn-primary border-0 shadow mb-3", type: "button" }, { default: c(() => [P, H]), _: 1 }), s("div", K, [s("div", U, [E, M, s("div", O, [s("table", V, [s("tbody", null, [s("tr", null, [F, s("td", null, e(t.exam_session.exam.title), 1)]), s("tr", null, [T, s("td", null, e(t.exam_session.exam.lesson.title), 1)]), s("tr", null, [I, s("td", null, e(t.exam_session.exam.classroom.title), 1)]), s("tr", null, [J, s("td", null, e(t.exam_session.title), 1)]), s("tr", null, [Y, s("td", null, e(t.exam_session.start_time), 1)]), s("tr", null, [q, s("td", null, e(t.exam_session.end_time), 1)])])])])])]), s("div", z, [s("div", G, [Q, R, o(m, { href: `/admin/exam_sessions/${t.exam_session.id}/enrolle/create`, class: "btn btn-md btn-primary border-0 shadow me-2", type: "button" }, { default: c(() => [W, X]), _: 1 }, 8, ["href"]), s("div", Z, [s("table", $, [ss, ts, s("tbody", null, [(d(!0), r(h, null, v(t.exam_session.exam_groups.data, (n, u) => (d(), r("tr", { key: u }, [s("td", es, e(++u + (t.exam_session.exam_groups.current_page - 1) * t.exam_session.exam_groups.per_page), 1), s("td", null, e(n.student.name), 1), s("td", ns, e(n.student.classroom.title), 1), s("td", os, e(n.student.gender), 1), s("td", as, [s("button", { onClick: B(ms => l.destroy(t.exam_session.id, n.id), ["prevent"]), class: "btn btn-sm btn-danger border-0" }, ds, 8, ls)])]))), 128))])])]), o(w, { links: t.exam_session.exam_groups.links, align: "end" }, null, 8, ["links"])])])])])])], 64) }
const ws = C(j, [
    ["render", rs]
]);
export { ws as default };