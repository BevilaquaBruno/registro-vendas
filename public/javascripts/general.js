function returnBrazilianCurrency(value) {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
}

function returnBrazilianNumber(value) {
  return new Intl.NumberFormat('pt-BR').format(value);
}