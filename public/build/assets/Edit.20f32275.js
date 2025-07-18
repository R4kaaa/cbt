import { L as U } from "./Admin.3b832e96.js";
import { H as V, L as j, r as N, a as u, o as n, c as i, b as h, w as b, d as s, e as q, f as _, v, t as r, g as d, s as c, F as f, p as w, h as E, i as k } from "./app.e795c71f.js";
import { S as M } from "./sweetalert2.all.0276e8f3.js";
import { E as S } from "./Editor.9dfc951f.js";
import { _ as L } from "./_plugin-vue_export-helper.cdc0426e.js";
const A = { layout: U, components: { Head: V, Link: j, Editor: S }, props: { errors: Object, exam: Object, lessons: Array, classrooms: Array }, setup(l) { const o = N({ title: l.exam.title, lesson_id: l.exam.lesson_id, classroom_id: l.exam.classroom_id, duration: l.exam.duration, description: l.exam.description, random_question: l.exam.random_question, random_answer: l.exam.random_answer, show_answer: l.exam.show_answer }); return { form: o, submit: () => { E.Inertia.put(`/admin/exams/${l.exam.id}`, { title: o.title, lesson_id: o.lesson_id, classroom_id: o.classroom_id, duration: o.duration, description: o.description, random_question: o.random_question, random_answer: o.random_answer, show_answer: o.show_answer }, { onSuccess: () => { M.fire({ title: "Success!", text: "Ujian Berhasil Diupdate!.", icon: "success", showConfirmButton: !1, timer: 2e3 }) } }) } } } },
    D = s("title", null, "Edit Ujian - Aplikasi Ujian Online", -1),
    Y = { class: "container-fluid mb-5 mt-5" },
    B = { class: "row" },
    H = { class: "col-md-12" },
    C = s("i", { class: "fa fa-long-arrow-alt-left me-2" }, null, -1),
    O = k(" Kembali"),
    T = { class: "card border-0 shadow" },
    z = { class: "card-body" },
    F = s("h5", null, [s("i", { class: "fa fa-edit" }), k(" Edit Ujian")], -1),
    K = s("hr", null, null, -1),
    I = { class: "mb-4" },
    J = s("label", null, "Nama Ujian", -1),
    P = { key: 0, class: "alert alert-danger mt-2" },
    R = { class: "row" },
    G = { class: "col-md-6" },
    Q = { class: "mb-4" },
    W = s("label", null, "Materi", -1),
    X = ["value"],
    Z = { key: 0, class: "alert alert-danger mt-2" },
    p = { class: "col-md-6" },
    $ = { class: "mb-4" },
    ss = s("label", null, "Kelas", -1),
    os = ["value"],
    ts = { key: 0, class: "alert alert-danger mt-2" },
    es = { class: "mb-4" },
    as = s("label", null, "Deskripsi", -1),
    ns = { key: 0, class: "alert alert-danger mt-2" },
    is = { class: "row" },
    ls = { class: "col-md-6" },
    rs = { class: "mb-4" },
    ds = s("label", null, "Acak Soal", -1),
    _s = s("option", { value: "Y" }, "Y", -1),
    cs = s("option", { value: "N" }, "N", -1),
    ms = [_s, cs],
    us = { key: 0, class: "alert alert-danger mt-2" },
    hs = { class: "col-md-6" },
    fs = { class: "mb-4" },
    bs = s("label", null, "Acak Jawaban", -1),
    vs = s("option", { value: "Y" }, "Y", -1),
    ws = s("option", { value: "N" }, "N", -1),
    ks = [vs, ws],
    ys = { key: 0, class: "alert alert-danger mt-2" },
    xs = { class: "row" },
    gs = { class: "col-md-6" },
    Us = { class: "mb-4" },
    Vs = s("label", null, "Tampilkan Hasil", -1),
    js = s("option", { value: "Y" }, "Y", -1),
    Ns = s("option", { value: "N" }, "N", -1),
    qs = [js, Ns],
    Es = { key: 0, class: "alert alert-danger mt-2" },
    Ms = { class: "col-md-6" },
    Ss = { class: "mb-4" },
    Ls = s("label", null, "Durasi (Menit)", -1),
    As = { key: 0, class: "alert alert-danger mt-2" },
    Ds = s("button", { type: "submit", class: "btn btn-md btn-primary border-0 shadow me-2" }, "Update", -1),
    Ys = s("button", { type: "reset", class: "btn btn-md btn-warning border-0 shadow" }, "Reset", -1);

function Bs(l, o, e, a, Hs, Cs) { const y = u("Head"),
        x = u("Link"),
        g = u("Editor"); return n(), i(f, null, [h(y, null, { default: b(() => [D]), _: 1 }), s("div", Y, [s("div", B, [s("div", H, [h(x, { href: "/admin/exams", class: "btn btn-md btn-primary border-0 shadow mb-3", type: "button" }, { default: b(() => [C, O]), _: 1 }), s("div", T, [s("div", z, [F, K, s("form", { onSubmit: o[8] || (o[8] = q((...t) => a.submit && a.submit(...t), ["prevent"])) }, [s("div", I, [J, _(s("input", { type: "text", class: "form-control", placeholder: "Masukkan Nama Ujian", "onUpdate:modelValue": o[0] || (o[0] = t => a.form.title = t) }, null, 512), [
        [v, a.form.title]
    ]), e.errors.title ? (n(), i("div", P, r(e.errors.title), 1)) : d("", !0)]), s("div", R, [s("div", G, [s("div", Q, [W, _(s("select", { class: "form-select", "onUpdate:modelValue": o[1] || (o[1] = t => a.form.lesson_id = t) }, [(n(!0), i(f, null, w(e.lessons, (t, m) => (n(), i("option", { key: m, value: t.id }, r(t.title), 9, X))), 128))], 512), [
        [c, a.form.lesson_id]
    ]), e.errors.lesson_id ? (n(), i("div", Z, r(e.errors.lesson_id), 1)) : d("", !0)])]), s("div", p, [s("div", $, [ss, _(s("select", { class: "form-select", "onUpdate:modelValue": o[2] || (o[2] = t => a.form.classroom_id = t) }, [(n(!0), i(f, null, w(e.classrooms, (t, m) => (n(), i("option", { key: m, value: t.id }, r(t.title), 9, os))), 128))], 512), [
        [c, a.form.classroom_id]
    ]), e.errors.classroom_id ? (n(), i("div", ts, r(e.errors.classroom_id), 1)) : d("", !0)])])]), s("div", es, [as, h(g, { "api-key": "dwq3i99zdbda10alithjifi49cxh7qnk222xfozi26pdxv3o", modelValue: a.form.description, "onUpdate:modelValue": o[3] || (o[3] = t => a.form.description = t), init: { menubar: !1, plugins: "lists link image emoticons", toolbar: "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons" } }, null, 8, ["modelValue"]), e.errors.description ? (n(), i("div", ns, r(e.errors.description), 1)) : d("", !0)]), s("div", is, [s("div", ls, [s("div", rs, [ds, _(s("select", { class: "form-select", "onUpdate:modelValue": o[4] || (o[4] = t => a.form.random_question = t) }, ms, 512), [
        [c, a.form.random_question]
    ]), e.errors.random_question ? (n(), i("div", us, r(e.errors.random_question), 1)) : d("", !0)])]), s("div", hs, [s("div", fs, [bs, _(s("select", { class: "form-select", "onUpdate:modelValue": o[5] || (o[5] = t => a.form.random_answer = t) }, ks, 512), [
        [c, a.form.random_answer]
    ]), e.errors.random_answer ? (n(), i("div", ys, r(e.errors.random_answer), 1)) : d("", !0)])])]), s("div", xs, [s("div", gs, [s("div", Us, [Vs, _(s("select", { class: "form-select", "onUpdate:modelValue": o[6] || (o[6] = t => a.form.show_answer = t) }, qs, 512), [
        [c, a.form.show_answer]
    ]), e.errors.show_answer ? (n(), i("div", Es, r(e.errors.show_answer), 1)) : d("", !0)])]), s("div", Ms, [s("div", Ss, [Ls, _(s("input", { type: "number", min: "1", class: "form-control", placeholder: "Masukkan Durasi Ujian (Menit)", "onUpdate:modelValue": o[7] || (o[7] = t => a.form.duration = t) }, null, 512), [
        [v, a.form.duration]
    ]), e.errors.duration ? (n(), i("div", As, r(e.errors.duration), 1)) : d("", !0)])])]), Ds, Ys], 32)])])])])])], 64) }
const Is = L(A, [
    ["render", Bs]
]);
export { Is as default };