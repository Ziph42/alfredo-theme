/* Alfredo Paterno theme: scroll animation, loader, page transitions */
(function () {
  var hasGsap = !!window.gsap;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fancy = hasGsap && !reduce;
  var bars = document.querySelectorAll('#wipe .bar');
  var stat = document.querySelector('#wipe .stat');
  var busy = false;

  /* ---------- Page transition (leave) ---------- */
  function leave(url) {
    try { sessionStorage.setItem('apWipe', '1'); } catch (e) {}
    if (!fancy || !bars.length) { location.href = url; return; }
    if (busy) return; busy = true;
    gsap.timeline({ onComplete: function () { location.href = url; } })
      .set(bars, { transformOrigin: 'bottom' })
      .to(bars, { scaleY: 1, duration: .45, ease: 'power3.in', stagger: { each: .12 } })
      .to({}, { duration: .05 });
  }
  window.addEventListener('pageshow', function (e) { if (e.persisted) { busy = false; if (bars.length) gsap.set(bars, { scaleY: 0 }); } });

  document.addEventListener('click', function (e) {
    var a = e.target.closest && e.target.closest('a[href]');
    if (!a || e.defaultPrevented || e.metaKey || e.ctrlKey || e.shiftKey || a.target === '_blank') return;
    var u;
    try { u = new URL(a.href, location.href); } catch (x) { return; }
    if (u.origin !== location.origin) return;
    if (u.pathname === location.pathname && u.hash) return;           // same-page anchor
    if (/\/wp-(admin|login)|\.(pdf|zip|jpg|png|mp4)$/i.test(u.pathname)) return;
    e.preventDefault();
    leave(a.href);
  });

  /* ---------- Page transition (arrive) ---------- */
  var arrived = false;
  try { arrived = sessionStorage.getItem('apWipe') === '1'; sessionStorage.removeItem('apWipe'); } catch (e) {}
  if (arrived && fancy && bars.length) {
    gsap.set(bars, { scaleY: 1, transformOrigin: 'top' });
    document.documentElement.classList.remove('ap-arrive');   // real bars now cover the screen, hand over in the same frame
    gsap.to(bars, { scaleY: 0, duration: .5, ease: 'power3.out', stagger: { each: .12 }, delay: .1 });
  }
  document.documentElement.classList.remove('ap-arrive');     // never leave the cover up if the animation can't run

  /* ---------- Project page intro ---------- */
  var title = document.getElementById('pTitle');
  if (title && fancy) {
    var words = title.textContent.trim().split(/\s+/);
    title.innerHTML = '';
    words.forEach(function (w) { var s = document.createElement('span'); s.textContent = w + ' '; title.appendChild(s); });
    gsap.timeline()
      .from('#pTitle span', { yPercent: 110, duration: .7, ease: 'power4.out', stagger: .08 }, arrived ? .35 : 0)
      .from('#pMeta,.proj .shot,.proj .content,.proj .next', { opacity: 0, y: 30, duration: .6, stagger: .1 }, '-=.5');
  }

  /* ---------- Home page ---------- */
  if (!fancy || !document.getElementById('hero')) return;
  gsap.registerPlugin(ScrollTrigger);

  var pre = document.getElementById('pre');
  var seen = false;
  try { seen = sessionStorage.getItem('apSeen') === '1'; sessionStorage.setItem('apSeen', '1'); } catch (e) {}
  var showLoader = pre && !seen && !arrived;

  function setupScroll() {
    gsap.set('#hBar', { transformOrigin: '50% 100%' });
    gsap.set('#hP', { transformOrigin: '0% 50%' });
    gsap.set('#hDot', { svgOrigin: '809.1 272.7' });
    var heroTl = gsap.timeline({ scrollTrigger: { trigger: '#hero', start: 'top top', end: '+=120%', scrub: true, pin: true } });
    if (document.getElementById('heroLogo')) {
      /* logo starts hidden, builds in (like the loader) as the words fade, then blurs away */
      gsap.set('#hDot', { scale: 0 }); gsap.set('#hP', { scaleX: 0, opacity: 0 }); gsap.set('#hBar', { scaleY: 0 });
      gsap.set('#hFoot', { y: 160, opacity: 0 }); gsap.set('#hSlab', { x: -260, y: -120, opacity: 0 });
      heroTl.to('#heroLogo', { opacity: .22, duration: .1 }, .3)
        .to('#hSlab', { x: 0, y: 0, opacity: 1, duration: .08, ease: 'power4.out' }, .3)
        .to('#hFoot', { y: 0, opacity: 1, duration: .07, ease: 'power4.out' }, .33)
        .to('#hBar', { scaleY: 1, duration: .07, ease: 'power3.out' }, .36)
        .to('#hP', { scaleX: 1, opacity: 1, duration: .08, ease: 'power3.out' }, .39)
        .to('#hDot', { scale: 1, duration: .08, ease: 'power2.out' }, .43)
        .to('#heroLogo', { filter: 'blur(16px)', opacity: 0, scale: 1.15, duration: .15, ease: 'power2.in' }, .6);
    }
    heroTl
      .to('#heroCopy', { scale: 3.2, filter: 'blur(18px)', opacity: 0, ease: 'power2.in' }, 0)
      .to('#ring', { scale: 2.4, opacity: .2, ease: 'none' }, 0)
      .to('#heroSub', { opacity: 0, y: 30 }, 0);


    var lines = gsap.utils.toArray('#stmt .l');
    var wls = gsap.utils.toArray('#stmt .wl');
    var st = gsap.timeline({ scrollTrigger: { trigger: '#about', start: 'top top', end: '+=320%', scrub: true, pin: true } });
    lines.forEach(function (l, i) { st.to(l, { opacity: 1, filter: 'blur(0px)', duration: 1 }, i); });
    var t0 = lines.length + .3;
    st.to(lines, { opacity: .11, filter: 'blur(5px)', duration: 1, ease: 'power2.inOut' }, t0);
    gsap.set('#about', { '--gdim': 0 });
    st.to('#about', { '--gdim': 1, duration: 1, ease: 'power2.inOut' }, t0);
    wls.forEach(function (l, j) {
      gsap.set(l.querySelectorAll('.w'), { yPercent: 110 });
      st.to(l, { opacity: 1, duration: .3 }, t0 + .5 + j * 1.1);
      st.to(l.querySelectorAll('.w'), { yPercent: 0, duration: 1, stagger: .32, ease: 'power3.out' }, t0 + .5 + j * 1.1);
    });
    st.to({}, { duration: .8 });

    gsap.utils.toArray('.row').forEach(function (row) {
      var band = row.querySelector('.band');
      ScrollTrigger.create({
        trigger: row, start: 'top 62%', end: 'bottom 38%',
        onToggle: function (self) {
          row.classList.toggle('on', self.isActive);
          gsap.to(band, { scaleX: self.isActive ? 1 : 0, duration: .5, ease: 'power3.out' });
        }
      });
    });
    gsap.from('.close h2', { y: 80, opacity: 0, scrollTrigger: { trigger: '.close', start: 'top 70%', end: 'top 20%', scrub: true } });
  }
  setupScroll();

  /* ---------- Loader: logo builds, blurry waves fill, circle opens onto hero ---------- */
  var red = document.getElementById('preRed');
  if (!showLoader) { if (pre) pre.remove(); if (red) red.remove(); return; }

  document.body.style.overflow = 'hidden';
  var st2 = { p: 0 }, cx = 0, cy = 0, max = 0;
  var WV = [{ A: 62, k: 1.6, s: 1.1, k2: 3.1, s2: 1.7 }, { A: 54, k: 2.2, s: -1.4, k2: 4, s2: 1.1 }, { A: 46, k: 1.9, s: 1.8, k2: 3.5, s2: -1.5 }];
  var WP = [document.getElementById('pw1'), document.getElementById('pw2'), document.getElementById('pw3')];
  function waveTick() {
    var t = performance.now() / 1000;
    WV.forEach(function (w, i) {
      var pts = [];
      for (var x = -200; x <= 1640; x += 40) {
        var u = x / 1440 * 6.2832;
        var y = w.A * (Math.sin(u * w.k + t * w.s) + .5 * Math.sin(u * w.k2 - t * w.s2 + i)) * (.85 + .15 * Math.sin(t * .8 + i * 2));
        pts.push(x + ',' + y.toFixed(1));
      }
      WP[i].setAttribute('d', 'M' + pts.join(' L') + ' L1640,3000 L-200,3000 Z');
    });
  }
  gsap.ticker.add(waveTick); waveTick();

  gsap.set(bars, { scaleY: 0 });
  gsap.set('.wg', { y: 1000, x: 0 });
  gsap.set('#pSlab', { x: -260, y: -120, opacity: 0, fill: '#DA3832' });
  gsap.set('#pP', { scaleX: 0, transformOrigin: '0% 50%', opacity: 0, fill: '#DA3832' });
  gsap.set('#pFoot', { y: 160, opacity: 0, fill: '#F4EBD9' });
  gsap.set('#pBar', { scaleY: 0, transformOrigin: '50% 100%', fill: '#F4EBD9' });
  gsap.set('#pDot', { scale: 0, svgOrigin: '809.1 272.7', fill: '#F4EBD9' });
  gsap.set('#hero .hero-copy', { opacity: 0, y: 40 });

  gsap.timeline()
    .to('#pSlab', { x: 0, y: 0, opacity: 1, duration: .7, ease: 'power4.out' }, 0)
    .to('#pFoot', { y: 0, opacity: 1, duration: .6, ease: 'power4.out' }, .25)
    .to('#pBar', { scaleY: 1, duration: .6, ease: 'power3.out' }, .45)
    .to('#pP', { scaleX: 1, opacity: 1, duration: .7, ease: 'power3.out' }, .7)
    .to('#pDot', { scale: 1, duration: .7, ease: 'power2.out' }, 1.2)
    .to('#g1', { y: -110, duration: 1.7, ease: 'power1.inOut' }, 1.5)
    .to('#g2', { y: -110, duration: 1.7, ease: 'power1.inOut' }, 1.7)
    .to('#g3', { y: -110, duration: 1.7, ease: 'power1.inOut' }, 1.9)
    .to(['#pSlab', '#pP'], { fill: '#F4EBD9', duration: .3 }, 2.4)
    .to(['#pFoot', '#pBar', '#pDot'], { fill: '#141718', duration: .3 }, 2.4)
    .add(function () {
      var b = document.getElementById('pDot').getBoundingClientRect();
      cx = b.left + b.width / 2; cy = b.top + b.height / 2;
      max = Math.hypot(Math.max(cx, innerWidth - cx), Math.max(cy, innerHeight - cy)) + 60;
      [pre, red].forEach(function (e) { e.style.setProperty('--cx', cx + 'px'); e.style.setProperty('--cy', cy + 'px'); });
    }, 3.65)
    .to(st2, { p: 1, duration: 1.2, ease: 'power2.in', onUpdate: function () {
      var r1 = st2.p * max, r2 = Math.max(-2, r1 - 90 * (1 + st2.p));
      pre.style.setProperty('--r', r1 + 'px'); red.style.setProperty('--r', r2 + 'px');
    } }, 3.7)
    .add(function () {
      pre.remove(); red.remove(); gsap.ticker.remove(waveTick);
      document.body.style.overflow = ''; window.scrollTo(0, 0); ScrollTrigger.refresh();
    }, 4.95)
    .to('#hero .hero-copy', { opacity: 1, y: 0, duration: .9, ease: 'power3.out' }, 4.25);
})();

(function(){
  var burger=document.getElementById('burger'),menu=document.getElementById('menu');if(!burger||!menu)return;
  function place(){var r=burger.getBoundingClientRect();menu.style.setProperty('--mx',(r.left+r.width/2)+'px');menu.style.setProperty('--my',(r.top+r.height/2)+'px');}
  function set(open){place();menu.classList.toggle('on',open);burger.classList.toggle('on',open);burger.setAttribute('aria-expanded',open);burger.setAttribute('aria-label',open?'Close menu':'Open menu');menu.setAttribute('aria-hidden',!open);document.body.classList.toggle('menu-open',open);}
  burger.addEventListener('click',function(){set(!menu.classList.contains('on'));});
  document.addEventListener('keydown',function(e){if(e.key==='Escape'&&menu.classList.contains('on'))set(false);});
  window.addEventListener('resize',place);place();
  menu.addEventListener('click',function(e){var a=e.target.closest('a');if(a){setTimeout(function(){set(false);},400);}});
})();

(function(){
  var NS='http://www.w3.org/2000/svg',COL=['#B83367','#B02A26','#DA3832'],CCOL=['#7A1F3C','#B83367','#B02A26','#DA3832','#F0645A'],
      reduce=window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches,corner=null,cps=null;
  function ease(x){return x*x*(3-2*x)}
  function mk(n,at,par){var e=document.createElementNS(NS,n);for(var k in at)e.setAttribute(k,at[k]);if(par)par.appendChild(e);return e}
  function cornerLayer(){
    if(corner)return corner;
    corner=mk('svg',{id:'ctacorner','aria-hidden':'true',preserveAspectRatio:'none'});
    cps=CCOL.map(function(c){return mk('path',{fill:c},corner)});
    document.body.appendChild(corner);return corner}
  function lock(a){
    a.style.minWidth='';var t=a.style.transition;a.style.transition='none';
    var w0=a.getBoundingClientRect().width;a.classList.add('on');
    var w1=a.getBoundingClientRect().width;a.classList.remove('on');
    a.style.minWidth=Math.ceil(Math.max(w0,w1))+'px';void a.offsetWidth;a.style.transition=t}
  function setup(a){
    if(a.dataset.wv)return;a.dataset.wv='1';
    var plain=a.classList.contains('wvlogo'),useCorner=!!a.closest('.nav');
    if(!plain)lock(a);if(!plain&&document.fonts&&document.fonts.ready)document.fonts.ready.then(function(){if(!a.classList.contains('on'))lock(a)});
    if(reduce){a.classList.add('rm');
      ['pointerenter','focus'].forEach(function(e){a.addEventListener(e,function(){a.classList.add('on')})});
      ['pointerleave','blur'].forEach(function(e){a.addEventListener(e,function(){a.classList.remove('on')})});return}
    var inn=null,s=null,ps=[];
    if(!plain){inn=document.createElement('span');inn.className='ctain';inn.setAttribute('aria-hidden','true');
      s=mk('svg',{'class':'ctafx'},inn);ps=COL.map(function(c){return mk('path',{fill:c},s)});
      a.insertBefore(inn,a.firstChild);}
    var w=0,h=0,cx=0,cy=0,sx=0,sy=0,p=0,tg=0,last=0,raf=0,T=0,VW=0,VH=0;
    function blob(path,x,y,r0,i,amp){
      var d='',N=96;
      for(var k=0;k<N;k++){
        var th=k/N*Math.PI*2,r=r0>0?r0+amp*Math.sin(th*5+T*1.7+i*1.7)+amp*.75*Math.sin(th*9-T*1.4+i)+amp*.45*Math.sin(th*13+T*0.9-i):0;
        d+=(k?'L':'M')+(x+Math.cos(th)*r).toFixed(1)+' '+(y+Math.sin(th)*r).toFixed(1)}
      path.setAttribute('d',d+'Z')}
    function draw(){
      var e=ease(p),R=e*(Math.hypot(w,h)+70);
      ps.forEach(function(path,i){blob(path,cx,cy,Math.max(0,R-i*18),i,5+i)});
      /* a wave growing out of the screen corner, on a layer behind the nav */
      if(!useCorner)return;
      var Rs=ease(Math.min(1,p*1.15))*Math.min(VW,VH)*(plain?.075:.135);
      cps.forEach(function(path,i){blob(path,sx,sy,Math.max(0,Rs-i*(plain?7:11)),i,(plain?10:17)+i*(plain?3:4))});
    }
    function view(){
      var r=a.getBoundingClientRect();w=r.width;h=r.height;
      VW=innerWidth;VH=innerHeight;
      if(s)s.setAttribute('viewBox','0 0 '+w+' '+h);
      if(useCorner)cornerLayer().setAttribute('viewBox','0 0 '+VW+' '+VH);
      if(plain){sx=r.left+r.width/2;sy=0}else{sx=(r.left+r.width/2<VW/2)?0:VW;sy=(r.top+r.height/2<VH/2)?0:VH}
      return r}
    function tick(t){
      var dt=Math.min(.05,(t-last)/1000||.016);last=t;T+=dt;
      p+=(tg?1:-1)*dt/1.05;p=Math.max(0,Math.min(1,p));draw();
      if(p!==tg||tg){raf=requestAnimationFrame(tick)}else{raf=0;if(corner)corner.style.display='none'}}
    function go(v){if(v&&corner)corner.style.display='';tg=v;if(!raf){last=performance.now();raf=requestAnimationFrame(tick)}}
    a.addEventListener('pointerenter',function(e){
      if(e.pointerType==='touch')return;
      var r=view();
      if(p===0){cx=(e.clientX-r.left<w/2)?0:w;cy=(e.clientY-r.top<h/2)?0:h}
      a.classList.add('on');go(1)});
    a.addEventListener('pointerleave',function(){go(0);a.classList.remove('on')});
    a.addEventListener('focus',function(){view();if(p===0){cx=w;cy=h}a.classList.add('on');go(1)});
    a.addEventListener('blur',function(){go(0);a.classList.remove('on')});
  }
  function init(){document.querySelectorAll('.cta,.wvlogo').forEach(setup)}
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',init);else init();
})();

// About page timeline
(function(){
  var tl=document.querySelector('.tl');if(!tl)return;
  var items=[].slice.call(tl.querySelectorAll('.tl-item')),rail=tl.querySelector('.tl-rail'),
      head=tl.querySelector('.tl-head'),list=tl.querySelector('.tl-list');
  if(!('IntersectionObserver' in window)){items.forEach(function(i){i.classList.add('in','on')});return}
  document.documentElement.classList.add('tl-js');
  // cards slide in once as they enter
  var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}})},{rootMargin:'0px 0px -15% 0px'});
  items.forEach(function(i){io.observe(i)});
  // the rail draws to the middle of the screen; dots light as the line reaches them
  var ticking=false;
  function measure(){
    var lr=list.getBoundingClientRect(),tr=tl.getBoundingClientRect();
    var top=lr.top-tr.top, first=items[0].querySelector('.tl-dot'), last=items[items.length-1].querySelector('.tl-dot');
    var a=first.getBoundingClientRect(), z=last.getBoundingClientRect();
    rail.style.top=(a.top+a.height/2-tr.top)+'px';
    rail.style.bottom=(tr.bottom-(z.top+z.height/2))+'px';
    var mid=innerHeight*0.55, rt=a.top+a.height/2, rb=z.top+z.height/2;
    var end=scrollY+innerHeight>=document.documentElement.scrollHeight-4;
    var p=end?1:Math.max(0,Math.min(1,(mid-rt)/Math.max(1,rb-rt)));
    rail.style.setProperty('--p',p.toFixed(4));
    items.forEach(function(it){var d=it.querySelector('.tl-dot').getBoundingClientRect();it.classList.toggle('on',end||d.top+d.height/2<=mid+1)});
    ticking=false;
  }
  function req(){if(!ticking){ticking=true;requestAnimationFrame(measure)}}
  addEventListener('scroll',req,{passive:true});addEventListener('resize',req);
  if(document.fonts&&document.fonts.ready)document.fonts.ready.then(req);
  measure();
})();

// Trusted by: each logo is sharp in the middle of the screen and blurs/fades toward the edges
(function(){
  var items=[].slice.call(document.querySelectorAll('.tr-grid li'));if(!items.length)return;
  if(window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches)return;
  var jit=items.map(function(_,i){return ((i*37)%11)/11*0.14-0.07}),ticking=false;
  function upd(){
    var vh=innerHeight;
    items.forEach(function(li,i){
      var r=li.getBoundingClientRect();
      if(r.bottom<-vh*.5||r.top>vh*1.5){li.style.opacity=0;return}
      var c=r.top+r.height/2,t=Math.abs(c-vh/2)/(vh/2)+jit[i];
      var k=Math.max(0,Math.min(1,(t-.3)/.6)),e=k*k*(3-2*k);
      li.style.opacity=(1-e).toFixed(3);
      li.style.filter=e>.01?'blur('+(e*14).toFixed(1)+'px)':'none';
      li.style.transform='translateY('+((c<vh/2?-1:1)*e*34).toFixed(1)+'px) scale('+(1-e*.1).toFixed(3)+')';
    });
    ticking=false;
  }
  function req(){if(!ticking){ticking=true;requestAnimationFrame(upd)}}
  addEventListener('scroll',req,{passive:true});addEventListener('resize',req);
  addEventListener('load',req);upd();
})();

// Project page: images fade up as they scroll into view
(function(){
  var imgs=[].slice.call(document.querySelectorAll('.proj .content img'));
  if(!imgs.length||!('IntersectionObserver' in window))return;
  if(window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches)return;
  var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}})},{rootMargin:'0px 0px -8% 0px'});
  imgs.forEach(function(im){if(im.getBoundingClientRect().top<innerHeight)return;im.classList.add('rv');io.observe(im)});
})();

// Project page: tag long images (emails, infographics) so they get a width limit instead of a height limit
(function(){
  var imgs=[].slice.call(document.querySelectorAll('.proj .content img, .proj .full img'));
  function check(im){ if(im.naturalWidth && im.naturalHeight/im.naturalWidth>=2){ im.classList.add('is-long'); } }
  imgs.forEach(function(im){ if(im.complete){check(im);} else { im.addEventListener('load',function(){check(im);}); } });
})();

// Videos without a thumbnail: show a frame from a little way in (the first frame is often a blank title colour),
// then start from the beginning when the visitor presses play.
(function(){
  var vids=[].slice.call(document.querySelectorAll('.proj video'));
  vids.forEach(function(v){
    if(v.getAttribute('poster'))return;
    var auto=false,userSeek=false,started=false;
    function peek(){ if(started||!isFinite(v.duration)||v.duration<2)return; auto=true; v.currentTime=Math.max(1,Math.min(v.duration*0.15,6)); }
    v.addEventListener('seeking',function(){ if(!auto)userSeek=true; });
    v.addEventListener('seeked',function(){ auto=false; });
    v.addEventListener('play',function(){ if(!started){ started=true; if(!userSeek&&v.currentTime>0){ v.currentTime=0; } } });
    if(v.readyState>=1){peek();}else{v.addEventListener('loadedmetadata',peek,{once:true});}
  });
})();

/* Chromatic aberration filters for animation thumbs */
(function(){
  if(document.getElementById('ap-ca-defs'))return;
  function f(id,dx,dy){return '<filter id="'+id+'" x="-5%" y="-5%" width="110%" height="110%" color-interpolation-filters="sRGB">'+
    '<feColorMatrix in="SourceGraphic" type="matrix" values="1 0 0 0 0  0 0 0 0 0  0 0 0 0 0  0 0 0 1 0" result="r"/>'+
    '<feOffset in="r" dx="'+dx+'" dy="'+dy+'" result="ro"/>'+
    '<feColorMatrix in="SourceGraphic" type="matrix" values="0 0 0 0 0  0 1 0 0 0  0 0 0 0 0  0 0 0 1 0" result="g"/>'+
    '<feColorMatrix in="SourceGraphic" type="matrix" values="0 0 0 0 0  0 0 0 0 0  0 0 1 0 0  0 0 0 1 0" result="b"/>'+
    '<feOffset in="b" dx="'+(-dx)+'" dy="'+(-dy)+'" result="bo"/>'+
    '<feBlend in="ro" in2="g" mode="screen" result="rg"/><feBlend in="rg" in2="bo" mode="screen"/></filter>';}
  var d=document.createElement('div');d.id='ap-ca-defs';d.setAttribute('aria-hidden','true');
  d.style.cssText='position:absolute;width:0;height:0;overflow:hidden';
  d.innerHTML='<svg width="0" height="0"><defs>'+f('ap-ca1',3,0)+f('ap-ca2',5,1)+f('ap-ca3',2,-1)+'</defs></svg>';
  (document.body||document.documentElement).appendChild(d);
})();

/* About stats: count up on scroll */
(function(){
  var list=document.querySelector('.ab-stats'); if(!list)return;
  var reduce=window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches;
  if(reduce||!('IntersectionObserver' in window))return;
  list.classList.add('js-count');
  var items=[].slice.call(list.querySelectorAll('li'));
  items.forEach(function(li){var b=li.querySelector('b');var m=b.textContent.match(/^(\D*)(\d+)(.*)$/);
    if(m){b.dataset.pre=m[1];b.dataset.n=m[2];b.dataset.suf=m[3];b.textContent=m[1]+'0'+m[3];}});
  var io=new IntersectionObserver(function(es){es.forEach(function(e){if(!e.isIntersecting)return;io.disconnect();
    items.forEach(function(li,i){li.classList.add('in');var b=li.querySelector('b');if(!b.dataset.n)return;
      var to=+b.dataset.n,dur=1400,t0=null,delay=i*120;
      setTimeout(function(){requestAnimationFrame(function step(t){if(!t0)t0=t;var p=Math.min(1,(t-t0)/dur);
        var v=Math.round(to*(1-Math.pow(1-p,3)));b.textContent=b.dataset.pre+v+b.dataset.suf;if(p<1)requestAnimationFrame(step);});},delay);});
  });},{threshold:.4});
  io.observe(list);
})();

/* Cursor haze follows the mouse */
(function(){
  if(!window.matchMedia||!matchMedia('(hover:hover) and (pointer:fine)').matches)return;
  var h=document.createElement('div');h.id='apHaze';h.setAttribute('aria-hidden','true');document.body.appendChild(h);
  var still=matchMedia('(prefers-reduced-motion: reduce)').matches,x=-200,y=-200,cx=x,cy=y,raf=0;
  function tick(){cx+=(x-cx)*(still?1:.22);cy+=(y-cy)*(still?1:.22);h.style.transform='translate3d('+cx+'px,'+cy+'px,0)';
    raf=(Math.abs(x-cx)+Math.abs(y-cy)>.3)?requestAnimationFrame(tick):0;}
  addEventListener('mousemove',function(e){x=e.clientX;y=e.clientY;h.classList.add('on');
    h.classList.toggle('link',!!(e.target.closest&&e.target.closest('a,button,[role=button],summary,label')));if(!raf)raf=requestAnimationFrame(tick);},{passive:true});
  document.addEventListener('mouseleave',function(){h.classList.remove('on');});
})();

/* Soft wavy gradient rising from the bottom as you reach the footer */
(function(){
  var NS='http://www.w3.org/2000/svg',reduce=window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches;
  var wrap=document.createElement('div');wrap.id='footWaves';wrap.setAttribute('aria-hidden','true');
  wrap.innerHTML='<svg viewBox="0 0 1440 400" preserveAspectRatio="none"><defs>'+
    '<linearGradient id="fw1" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#B83367"/><stop offset="1" stop-color="#3a1224"/></linearGradient>'+
    '<linearGradient id="fw2" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#DA3832"/><stop offset="1" stop-color="#7a1f3c"/></linearGradient>'+
    '<linearGradient id="fw3" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#F0645A"/><stop offset="1" stop-color="#B02A26"/></linearGradient>'+
    '</defs><path fill="url(#fw1)"/><path fill="url(#fw2)"/><path fill="url(#fw3)"/></svg>';
  document.body.appendChild(wrap);
  var P=wrap.querySelectorAll('path'),WV=[{b:120,A:34,k:1.5,s:.9},{b:190,A:28,k:2.1,s:-1.2},{b:260,A:22,k:1.8,s:1.5}],p=0,raf=0;
  function shape(t){for(var i=0;i<3;i++){var w=WV[i],d='M-40,400';for(var x=-40;x<=1480;x+=40){var u=x/1440*6.2832;
    d+=' L'+x+','+(w.b+w.A*(Math.sin(u*w.k+t*w.s+i)+.45*Math.sin(u*w.k*2.3-t*w.s*.8))).toFixed(1);}P[i].setAttribute('d',d+' L1480,400 Z');}}
  function tick(now){raf=0;shape(reduce?0:now/1000);if(p>0&&!reduce)raf=requestAnimationFrame(tick);}
  function onScroll(){var left=document.documentElement.scrollHeight-innerHeight-scrollY,zone=innerHeight*.6;
    p=Math.max(0,Math.min(1,1-left/zone));p=p*p*(3-2*p);
    wrap.style.transform='translateY('+((1-p)*100)+'%)';wrap.style.opacity=(p*.55).toFixed(3);
    if(p>0&&!raf)raf=requestAnimationFrame(tick);}
  shape(0);addEventListener('scroll',onScroll,{passive:true});addEventListener('resize',onScroll);onScroll();
})();
