/* Gallery filters (no dependencies). Tiles are plain links to each project page. */
(function () {
  var root = document.getElementById('gallery');
  if (!root) return;
  var tiles = Array.prototype.slice.call(root.querySelectorAll('[data-title]'));
  var chips = document.querySelectorAll('.filters .chips button');
  var sel = document.querySelector('.filters select');
  var count = document.querySelector('.filters .count');
  var state = { cat: 'all', cl: 'all' };

  function apply() {
    var n = 0;
    tiles.forEach(function (t) {
      var cats = (t.dataset.cat || '').split(' ');
      var ok = (state.cat === 'all' || cats.indexOf(state.cat) > -1) && (state.cl === 'all' || t.dataset.client === state.cl);
      t.classList.toggle('hide', !ok);
      if (ok) n++;
    });
    if (count) count.textContent = n + (n === 1 ? ' project' : ' projects');
  }
  chips.forEach(function (b) {
    b.addEventListener('click', function () {
      chips.forEach(function (x) { x.classList.remove('on'); });
      b.classList.add('on'); state.cat = b.dataset.cat; apply();
    });
  });
  if (sel) sel.addEventListener('change', function () { state.cl = sel.value; apply(); });
  apply();

})();
