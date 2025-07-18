import { L as u } from "./Admin.3b832e96.js";
import { H as f, L as h, r as p, a as r, o as i, c as l, b as d, w as c, d as t, e as v, f as w, v as k, t as y, g as x, F as j, h as S, i as m } from "./app.e795c71f.js";
import { S as g } from "./sweetalert2.all.0276e8f3.js";
import { _ as L } from "./_plugin-vue_export-helper.cdc0426e.js";
const N = { layout: u, components: { Head: f, Link: h }, props: { errors: Object }, setup() { const a = p({ title: "" }); return { form: a, submit: () => { S.Inertia.post("/admin/lessons", { title: a.title }, { onSuccess: () => { g.fire({ title: "Success!", text: "Pelajaran Berhasil Disimpan!.", icon: "success", showConfirmButton: !1, timer: 2e3 }) } }) } } } },
    B = t("title", null, "Tambah Materi - Aplikasi Ujian Online", -1),
    C = { class: "container-fluid mb-5 mt-5" },
    P = { class: "row" },
    V = { class: "col-md-12" },
    H = t("i", { class: "fa fa-long-arrow-alt-left me-2" }, null, -1),
    M = m(" Kembali"),
    T = { class: "card border-0 shadow" },
    D = { class: "card-body" },
    A = t("h5", null, [t("i", { class: "fa fa-bookmark" }), m(" Tambah Pelajaran")], -1),
    F = t("hr", null, null, -1),
    O = { class: "mb-4" },
    U = t("label", null, "Nama Pelajaran", -1),
    E = { key: 0, class: "alert alert-danger mt-2" },
    I = t("button", { type: "submit", class: "btn btn-md btn-primary border-0 shadow me-2" }, "Simpan", -1),
    K = t("button", { type: "reset", class: "btn btn-md btn-warning border-0 shadow" }, "Reset", -1);

function R(a, s, n, e, q, z) { const _ = r("Head"),
        b = r("Link"); return i(), l(j, null, [d(_, null, { default: c(() => [B]), _: 1 }), t("div", C, [t("div", P, [t("div", V, [d(b, { href: "/admin/lessons", class: "btn btn-md btn-primary border-0 shadow mb-3", type: "button" }, { default: c(() => [H, M]), _: 1 }), t("div", T, [t("div", D, [A, F, t("form", { onSubmit: s[1] || (s[1] = v((...o) => e.submit && e.submit(...o), ["prevent"])) }, [t("div", O, [U, w(t("input", { type: "text", class: "form-control", placeholder: "Masukkan Nama Pelajaran", "onUpdate:modelValue": s[0] || (s[0] = o => e.form.title = o) }, null, 512), [
        [k, e.form.title]
    ]), n.errors.title ? (i(), l("div", E, y(n.errors.title), 1)) : x("", !0)]), I, K], 32)])])])])])], 64) }
const X = L(N, [
    ["render", R]
]);
export { X as default };