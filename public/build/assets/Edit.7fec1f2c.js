import { L as b } from "./Admin.3b832e96.js";
import { H as f, L as h, r as p, a as i, o as r, c as l, b as d, w as c, d as t, e as v, f as w, v as k, t as y, g as j, F as x, h as g, i as m } from "./app.e795c71f.js";
import { S as L } from "./sweetalert2.all.0276e8f3.js";
import { _ as N } from "./_plugin-vue_export-helper.cdc0426e.js";
const S = { layout: b, components: { Head: f, Link: h }, props: { errors: Object, lesson: Object }, setup(o) { const s = p({ title: o.lesson.title }); return { form: s, submit: () => { g.Inertia.put(`/admin/lessons/${o.lesson.id}`, { title: s.title }, { onSuccess: () => { L.fire({ title: "Success!", text: "Pelajaran Berhasil Diupdate!.", icon: "success", showConfirmButton: !1, timer: 2e3 }) } }) } } } },
    B = t("title", null, "Edit Materi - Aplikasi Ujian Online", -1),
    P = { class: "container-fluid mb-5 mt-5" },
    V = { class: "row" },
    C = { class: "col-md-12" },
    E = t("i", { class: "fa fa-long-arrow-alt-left me-2" }, null, -1),
    H = m(" Kembali"),
    M = { class: "card border-0 shadow" },
    D = { class: "card-body" },
    O = t("h5", null, [t("i", { class: "fa fa-bookmark" }), m(" Edit Pelajaran")], -1),
    U = t("hr", null, null, -1),
    A = { class: "mb-4" },
    F = t("label", null, "Nama Pelajaran", -1),
    T = { key: 0, class: "alert alert-danger mt-2" },
    I = t("button", { type: "submit", class: "btn btn-md btn-primary border-0 shadow me-2" }, "Update", -1),
    K = t("button", { type: "reset", class: "btn btn-md btn-warning border-0 shadow" }, "Reset", -1);

function R(o, s, a, e, q, z) { const _ = i("Head"),
        u = i("Link"); return r(), l(x, null, [d(_, null, { default: c(() => [B]), _: 1 }), t("div", P, [t("div", V, [t("div", C, [d(u, { href: "/admin/lessons", class: "btn btn-md btn-primary border-0 shadow mb-3", type: "button" }, { default: c(() => [E, H]), _: 1 }), t("div", M, [t("div", D, [O, U, t("form", { onSubmit: s[1] || (s[1] = v((...n) => e.submit && e.submit(...n), ["prevent"])) }, [t("div", A, [F, w(t("input", { type: "text", class: "form-control", placeholder: "Masukkan Nama Pelajaran", "onUpdate:modelValue": s[0] || (s[0] = n => e.form.title = n) }, null, 512), [
        [k, e.form.title]
    ]), a.errors.title ? (r(), l("div", T, y(a.errors.title), 1)) : j("", !0)]), I, K], 32)])])])])])], 64) }
const X = N(S, [
    ["render", R]
]);
export { X as default };