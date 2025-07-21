module.exports = {
    presets: [
      '@babel/preset-env',
      ['@babel/preset-react', {
        pragma: 'h',
        pragmaFrag: 'Fragment',
        runtime: 'classic' // Definir explicitamente o runtime como classic
      }]
    ]
  };
