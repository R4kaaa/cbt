import { L as V } from "./Admin.b5dfe990.js";
import { H as U, L as j, m as g, r as A, a as p, o as n, c as a, b as _, w as v, d as e, e as S, f as c, a2 as h, t as m, g as d, F as q, s as E, q as b, h as P, i as x, j as z } from "./app.c6958972.js";
import { S as B } from "./sweetalert2.all.1d02a522.js";
import { E as L } from "./Editor.d079d9f6.js";
import { _ as M } from "./_plugin-vue_export-helper.cdc0426e.js";
const I = {
        layout: V,
        components: { Head: U, Link: j, Editor: L },
        props: { errors: Object, exam: Object, question: Object },
        setup(r) {
            const i = g(r.question.question_image),
                s = g(r.question.audio_file),
                t = g(null),
                y = g(null),
                l = A({ question: r.question.question, option_1: r.question.option_1, option_2: r.question.option_2, option_3: r.question.option_3, option_4: r.question.option_4, option_5: r.question.option_5, question_type: r.question.question_type || "single", media_type: r.question.media_type || "none", answer: r.question.answer, answers: Array.isArray(r.question.answers) ? r.question.answers : r.question.answers ? r.question.answers.split(",") : [], essay_answer: r.question.essay_answer || "", question_image: null, audio_file: null });
            return {
                form: l,
                currentImage: i,
                currentAudio: s,
                imagePreview: t,
                audioPreview: y,
                handleImageUpload: o => {
                    const u = o.target.files[0];
                    u && (l.question_image = u, t.value = URL.createObjectURL(u), l.audio_file = null, y.value = null)
                },
                handleAudioUpload: o => {
                    const u = o.target.files[0];
                    u && (l.audio_file = u, y.value = URL.createObjectURL(u), l.question_image = null, t.value = null)
                },
                submit: () => {
                    const o = new FormData;
                    o.append("question", l.question), o.append("question_type", l.question_type), o.append("media_type", l.media_type), (l.question_type === "single" || l.question_type === "multiple") && (o.append("option_1", l.option_1), o.append("option_2", l.option_2), o.append("option_3", l.option_3), o.append("option_4", l.option_4), o.append("option_5", l.option_5)), l.question_type === "single" ? o.append("answer", l.answer) : l.question_type === "multiple" ? l.answers.forEach(u => { o.append("answers[]", u) }) : l.question_type === "essay" && o.append("essay_answer", l.essay_answer), l.media_type === "image" && l.question_image && o.append("question_image", l.question_image), l.media_type === "audio" && l.audio_file && o.append("audio_file", l.audio_file), P.Inertia.post(`/admin/exams/${r.exam.id}/questions/${r.question.id}/update`, o, { forceFormData: !0, preserveScroll: !0, preserveState: !1, onSuccess: () => { B.fire({ title: "Success!", text: "Soal Ujian Berhasil Diupdate!", icon: "success", showConfirmButton: !1, timer: 2e3 }) } })
                }
            }
        }
    },
    G = e("title", null, "Edit Soal Ujian - Aplikasi Ujian Online", -1),
    D = { class: "container-fluid mb-5 mt-5" },
    C = { class: "row" },
    F = { class: "col-md-12" },
    J = e("i", { class: "fa fa-long-arrow-alt-left me-2" }, null, -1),
    O = x(" Kembali"),
    N = { class: "card border-0 shadow" },
    R = { class: "card-body" },
    T = e("h5", null, [e("i", { class: "fa fa-question-circle" }), x(" Edit Soal Ujian")], -1),
    H = e("hr", null, null, -1),
    K = { class: "mb-3" },
    W = e("label", { class: "form-label fw-bold" }, "Tipe Soal", -1),
    Q = { class: "d-flex" },
    X = { class: "form-check me-4" },
    Y = e("label", { class: "form-check-label", for: "single" }, " Jawaban Tunggal ", -1),
    Z = { class: "form-check me-4" },
    $ = e("label", { class: "form-check-label", for: "multiple" }, " Jawaban Ganda ", -1),
    ee = { class: "form-check" },
    te = e("label", { class: "form-check-label", for: "essay" }, " Essay ", -1),
    oe = { key: 0, class: "mt-2 text-danger" },
    ie = { class: "mb-3" },
    se = e("label", { class: "form-label fw-bold" }, "Tipe Media", -1),
    le = { class: "d-flex" },
    ne = { class: "form-check me-4" },
    ae = e("label", { class: "form-check-label", for: "none" }, " Tanpa Media ", -1),
    de = { class: "form-check me-4" },
    re = e("label", { class: "form-check-label", for: "image" }, " Gambar ", -1),
    me = { class: "form-check" },
    ce = e("label", { class: "form-check-label", for: "audio" }, " Audio (Listening) ", -1),
    ue = { key: 0, class: "mt-2 text-danger" },
    _e = { key: 0, class: "mb-3" },
    fe = e("label", { class: "form-label fw-bold" }, "Upload Gambar", -1),
    he = e("small", { class: "text-muted" }, "Format: JPG, JPEG, PNG, GIF. Maksimal: 2MB", -1),
    be = { key: 0, class: "mt-2 text-danger" },
    ye = { key: 1, class: "mt-3" },
    ge = ["src"],
    pe = { key: 2, class: "mt-3" },
    ke = e("p", { class: "mb-2" }, "Gambar saat ini:", -1),
    we = ["src"],
    ve = { key: 1, class: "mb-3" },
    qe = e("label", { class: "form-label fw-bold" }, "Upload Audio (Listening)", -1),
    xe = e("small", { class: "text-muted" }, "Format: MP3, WAV, OGG. Maksimal: 10MB", -1),
    Ve = { key: 0, class: "mt-2 text-danger" },
    Ue = { key: 1, class: "mt-3" },
    je = ["src"],
    Ae = { key: 2, class: "mt-3" },
    Se = e("p", { class: "mb-2" }, "Audio saat ini:", -1),
    Ee = ["src"],
    Pe = { class: "table-responsive mb-4" },
    ze = { class: "table table-bordered table-centered table-nowrap mb-0 rounded" },
    Be = e("td", { style: { width: "20%" }, class: "fw-bold" }, "Soal", -1),
    Le = { key: 0, class: "mt-2 text-danger" },
    Me = e("td", { style: { width: "20%" }, class: "fw-bold" }, "Pilihan A", -1),
    Ie = { key: 0, class: "mt-2 text-danger" },
    Ge = e("td", { style: { width: "20%" }, class: "fw-bold" }, "Pilihan B", -1),
    De = { key: 0, class: "mt-2 text-danger" },
    Ce = e("td", { style: { width: "20%" }, class: "fw-bold" }, "Pilihan C", -1),
    Fe = { key: 0, class: "mt-2 text-danger" },
    Je = e("td", { style: { width: "20%" }, class: "fw-bold" }, "Pilihan D", -1),
    Oe = { key: 0, class: "mt-2 text-danger" },
    Ne = e("td", { style: { width: "20%" }, class: "fw-bold" }, "Pilihan E", -1),
    Re = { key: 0, class: "mt-2 text-danger" },
    Te = { key: 0 },
    He = e("td", { style: { width: "20%" }, class: "fw-bold" }, "Jawaban Benar", -1),
    Ke = z('<option value="1">A</option><option value="2">B</option><option value="3">C</option><option value="4">D</option><option value="5">E</option>', 5),
    We = [Ke],
    Qe = { key: 0, class: "mt-2 text-danger" },
    Xe = { key: 1 },
    Ye = e("td", { style: { width: "20%" }, class: "fw-bold" }, "Jawaban Benar", -1),
    Ze = { class: "form-check" },
    $e = e("label", { class: "form-check-label", for: "answer1" }, " A ", -1),
    et = { class: "form-check" },
    tt = e("label", { class: "form-check-label", for: "answer2" }, " B ", -1),
    ot = { class: "form-check" },
    it = e("label", { class: "form-check-label", for: "answer3" }, " C ", -1),
    st = { class: "form-check" },
    lt = e("label", { class: "form-check-label", for: "answer4" }, " D ", -1),
    nt = { class: "form-check" },
    at = e("label", { class: "form-check-label", for: "answer5" }, " E ", -1),
    dt = { key: 0, class: "mt-2 text-danger" },
    rt = { key: 1 },
    mt = e("td", { style: { width: "20%" }, class: "fw-bold" }, "Kunci Jawaban Essay", -1),
    ct = { key: 0, class: "mt-2 text-danger" },
    ut = e("small", { class: "text-muted mt-2" }, "Masukkan kunci jawaban atau panduan penilaian untuk soal essay.", -1),
    _t = e("button", { type: "submit", class: "btn btn-md btn-primary border-0 shadow me-2" }, "Simpan", -1),
    ft = e("button", { type: "reset", class: "btn btn-md btn-warning border-0 shadow" }, "Reset", -1);

function ht(r, i, s, t, y, l) {
    const k = p("Head"),
        w = p("Link"),
        f = p("Editor");
    return n(), a(q, null, [_(k, null, { default: v(() => [G]), _: 1 }), e("div", D, [e("div", C, [e("div", F, [_(w, { href: `/admin/exams/${s.exam.id}`, class: "btn btn-md btn-primary border-0 shadow mb-3", type: "button" }, { default: v(() => [J, O]), _: 1 }, 8, ["href"]), e("div", N, [e("div", R, [T, H, e("form", { onSubmit: i[21] || (i[21] = S((...o) => t.submit && t.submit(...o), ["prevent"])), enctype: "multipart/form-data" }, [e("div", K, [W, e("div", Q, [e("div", X, [c(e("input", { class: "form-check-input", type: "radio", "onUpdate:modelValue": i[0] || (i[0] = o => t.form.question_type = o), value: "single", id: "single" }, null, 512), [
        [h, t.form.question_type]
    ]), Y]), e("div", Z, [c(e("input", { class: "form-check-input", type: "radio", "onUpdate:modelValue": i[1] || (i[1] = o => t.form.question_type = o), value: "multiple", id: "multiple" }, null, 512), [
        [h, t.form.question_type]
    ]), $]), e("div", ee, [c(e("input", { class: "form-check-input", type: "radio", "onUpdate:modelValue": i[2] || (i[2] = o => t.form.question_type = o), value: "essay", id: "essay" }, null, 512), [
        [h, t.form.question_type]
    ]), te])]), s.errors.question_type ? (n(), a("div", oe, m(s.errors.question_type), 1)) : d("", !0)]), e("div", ie, [se, e("div", le, [e("div", ne, [c(e("input", { class: "form-check-input", type: "radio", "onUpdate:modelValue": i[3] || (i[3] = o => t.form.media_type = o), value: "none", id: "none" }, null, 512), [
        [h, t.form.media_type]
    ]), ae]), e("div", de, [c(e("input", { class: "form-check-input", type: "radio", "onUpdate:modelValue": i[4] || (i[4] = o => t.form.media_type = o), value: "image", id: "image" }, null, 512), [
        [h, t.form.media_type]
    ]), re]), e("div", me, [c(e("input", { class: "form-check-input", type: "radio", "onUpdate:modelValue": i[5] || (i[5] = o => t.form.media_type = o), value: "audio", id: "audio" }, null, 512), [
        [h, t.form.media_type]
    ]), ce])]), s.errors.media_type ? (n(), a("div", ue, m(s.errors.media_type), 1)) : d("", !0)]), t.form.media_type === "image" ? (n(), a("div", _e, [fe, e("input", { type: "file", class: "form-control", onInput: i[6] || (i[6] = (...o) => t.handleImageUpload && t.handleImageUpload(...o)), accept: "image/*" }, null, 32), he, s.errors.question_image ? (n(), a("div", be, m(s.errors.question_image), 1)) : d("", !0), t.imagePreview ? (n(), a("div", ye, [e("img", { src: t.imagePreview, class: "img-fluid", style: { "max-height": "200px" } }, null, 8, ge)])) : t.currentImage ? (n(), a("div", pe, [ke, e("img", { src: "/storage/questions/" + t.currentImage, class: "img-fluid", style: { "max-height": "200px" } }, null, 8, we)])) : d("", !0)])) : d("", !0), t.form.media_type === "audio" ? (n(), a("div", ve, [qe, e("input", { type: "file", class: "form-control", onInput: i[7] || (i[7] = (...o) => t.handleAudioUpload && t.handleAudioUpload(...o)), accept: "audio/*" }, null, 32), xe, s.errors.audio_file ? (n(), a("div", Ve, m(s.errors.audio_file), 1)) : d("", !0), t.audioPreview ? (n(), a("div", Ue, [e("audio", { controls: "", src: t.audioPreview }, null, 8, je)])) : t.currentAudio ? (n(), a("div", Ae, [Se, e("audio", { controls: "", src: "/storage/questions/" + t.currentAudio }, null, 8, Ee)])) : d("", !0)])) : d("", !0), e("div", Pe, [e("table", ze, [e("tbody", null, [e("tr", null, [Be, e("td", null, [_(f, { "api-key": "f4g16s2kaw96ta82x5udni28fxmdk833fkdpwdduyrzb20gr", modelValue: t.form.question, "onUpdate:modelValue": i[8] || (i[8] = o => t.form.question = o), init: { menubar: !1, plugins: "lists link image emoticons", toolbar: "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons" } }, null, 8, ["modelValue"]), s.errors.question ? (n(), a("div", Le, m(s.errors.question), 1)) : d("", !0)])]), t.form.question_type === "single" || t.form.question_type === "multiple" ? (n(), a(q, { key: 0 }, [e("tr", null, [Me, e("td", null, [_(f, { "api-key": "f4g16s2kaw96ta82x5udni28fxmdk833fkdpwdduyrzb20gr", modelValue: t.form.option_1, "onUpdate:modelValue": i[9] || (i[9] = o => t.form.option_1 = o), init: { height: 130, menubar: !1, plugins: "lists link image emoticons", toolbar: "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons" } }, null, 8, ["modelValue"]), s.errors.option_1 ? (n(), a("div", Ie, m(s.errors.option_1), 1)) : d("", !0)])]), e("tr", null, [Ge, e("td", null, [_(f, { "api-key": "f4g16s2kaw96ta82x5udni28fxmdk833fkdpwdduyrzb20gr", modelValue: t.form.option_2, "onUpdate:modelValue": i[10] || (i[10] = o => t.form.option_2 = o), init: { height: 130, menubar: !1, plugins: "lists link image emoticons", toolbar: "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons" } }, null, 8, ["modelValue"]), s.errors.option_2 ? (n(), a("div", De, m(s.errors.option_2), 1)) : d("", !0)])]), e("tr", null, [Ce, e("td", null, [_(f, { "api-key": "f4g16s2kaw96ta82x5udni28fxmdk833fkdpwdduyrzb20gr", modelValue: t.form.option_3, "onUpdate:modelValue": i[11] || (i[11] = o => t.form.option_3 = o), init: { height: 130, menubar: !1, plugins: "lists link image emoticons", toolbar: "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons" } }, null, 8, ["modelValue"]), s.errors.option_3 ? (n(), a("div", Fe, m(s.errors.option_3), 1)) : d("", !0)])]), e("tr", null, [Je, e("td", null, [_(f, { "api-key": "f4g16s2kaw96ta82x5udni28fxmdk833fkdpwdduyrzb20gr", modelValue: t.form.option_4, "onUpdate:modelValue": i[12] || (i[12] = o => t.form.option_4 = o), init: { height: 130, menubar: !1, plugins: "lists link image emoticons", toolbar: "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons" } }, null, 8, ["modelValue"]), s.errors.option_4 ? (n(), a("div", Oe, m(s.errors.option_4), 1)) : d("", !0)])]), e("tr", null, [Ne, e("td", null, [_(f, { "api-key": "f4g16s2kaw96ta82x5udni28fxmdk833fkdpwdduyrzb20gr", modelValue: t.form.option_5, "onUpdate:modelValue": i[13] || (i[13] = o => t.form.option_5 = o), init: { height: 130, menubar: !1, plugins: "lists link image emoticons", toolbar: "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons" } }, null, 8, ["modelValue"]), s.errors.option_5 ? (n(), a("div", Re, m(s.errors.option_5), 1)) : d("", !0)])]), t.form.question_type === "single" ? (n(), a("tr", Te, [He, e("td", null, [c(e("select", { class: "form-control", "onUpdate:modelValue": i[14] || (i[14] = o => t.form.answer = o) }, We, 512), [
        [E, t.form.answer]
    ]), s.errors.answer ? (n(), a("div", Qe, m(s.errors.answer), 1)) : d("", !0)])])) : d("", !0), t.form.question_type === "multiple" ? (n(), a("tr", Xe, [Ye, e("td", null, [e("div", Ze, [c(e("input", { class: "form-check-input", type: "checkbox", "onUpdate:modelValue": i[15] || (i[15] = o => t.form.answers = o), value: "1", id: "answer1" }, null, 512), [
        [b, t.form.answers]
    ]), $e]), e("div", et, [c(e("input", { class: "form-check-input", type: "checkbox", "onUpdate:modelValue": i[16] || (i[16] = o => t.form.answers = o), value: "2", id: "answer2" }, null, 512), [
        [b, t.form.answers]
    ]), tt]), e("div", ot, [c(e("input", { class: "form-check-input", type: "checkbox", "onUpdate:modelValue": i[17] || (i[17] = o => t.form.answers = o), value: "3", id: "answer3" }, null, 512), [
        [b, t.form.answers]
    ]), it]), e("div", st, [c(e("input", { class: "form-check-input", type: "checkbox", "onUpdate:modelValue": i[18] || (i[18] = o => t.form.answers = o), value: "4", id: "answer4" }, null, 512), [
        [b, t.form.answers]
    ]), lt]), e("div", nt, [c(e("input", { class: "form-check-input", type: "checkbox", "onUpdate:modelValue": i[19] || (i[19] = o => t.form.answers = o), value: "5", id: "answer5" }, null, 512), [
        [b, t.form.answers]
    ]), at]), s.errors.answers ? (n(), a("div", dt, m(s.errors.answers), 1)) : d("", !0)])])) : d("", !0)], 64)) : d("", !0), t.form.question_type === "essay" ? (n(), a("tr", rt, [mt, e("td", null, [_(f, { "api-key": "f4g16s2kaw96ta82x5udni28fxmdk833fkdpwdduyrzb20gr", modelValue: t.form.essay_answer, "onUpdate:modelValue": i[20] || (i[20] = o => t.form.essay_answer = o), init: { height: 200, menubar: !1, plugins: "lists link image emoticons", toolbar: "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons" } }, null, 8, ["modelValue"]), s.errors.essay_answer ? (n(), a("div", ct, m(s.errors.essay_answer), 1)) : d("", !0), ut])])) : d("", !0)])])]), _t, ft], 32)])])])])])], 64)
}
const wt = M(I, [
    ["render", ht]
]);
export { wt as default };