$(document).ready(function () {
  var sobre = $("#sobre");
  var btn_abrir = $("#abrir");
  var btn_reiniciar = $("#reiniciar");

  sobre.click(function () {
    abrir();
  });
  btn_abrir.click(function () {
    abrir();
  });
  btn_reiniciar.click(function () {
    cerrar();
  });

  function abrir() {
    sobre.addClass("abrir").removeClass("cerrar");
  }
  function cerrar() {
    sobre.addClass("cerrar").removeClass("abrir");
  }
});