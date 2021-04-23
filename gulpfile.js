const { src, dest } = require("gulp");
const aglio = require("gulp-aglio");

const compileBlueprint = () =>
  src("src/api/*.md")
    .pipe(aglio({template: "default"}))
    .pipe(dest("pub/api"));

exports.default = compileBlueprint;
