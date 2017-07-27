module.exports = {
  module: {
    loaders: [
      { test: /\.html$/, loader: "raw-loader" }
    ]
  },
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.js'
    }
  }
};