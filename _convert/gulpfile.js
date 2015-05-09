var vfs = require('vinyl-fs');
var converter = require('sass-convert');

vfs.src('./input/**/*.+(sass|scss|css)')
  .pipe(converter({
    from: 'sass',
    to: 'scss',
  }))
  .pipe(vfs.dest('./output'));
