const inputSlug = document.querySelector(".input__slug");
const output = document.querySelector(".output");
const a = "àáäâèéëêìíïîòóöôùúüûñçßÿỳýœæŕśńṕẃǵǹḿǘẍźḧ";
const b = "aaaaeeeeiiiioooouuuuncsyyyoarsnpwgnmuxzh";
const p = new RegExp(a.split("").join("|"), "g");
inputSlug.addEventListener("input", (e) => {
  output.value = inputSlug.value
    .replace(/[\s_]+/g, "-")
    .replace(p, (c) => b.charAt(a.indexOf(c)))
    .replace(/[^\w-]+/g, "")
    .replace(/--+/g, "-")
    .replace(/&/g, `-and-`)
    .replace(/\s{2,}/g, "-")
    .replace(/^-+|-+$/g, "")
    .toString()
    .toLowerCase();
});