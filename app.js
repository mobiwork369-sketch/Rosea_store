const fa = n => new Intl.NumberFormat('fa-IR').format(n);
function toast(message){const el=document.getElementById('toast');if(!el)return;el.textContent=message;el.classList.add('show');setTimeout(()=>el.classList.remove('show'),2400)}
function getCart(){try{return JSON.parse(localStorage.getItem('rosea_cart')||'[]')}catch(e){return []}}
function setCart(cart){localStorage.setItem('rosea_cart',JSON.stringify(cart));updateCartCount()}
function updateCartCount(){const el=document.getElementById('cart-count');if(el){const count=getCart().reduce((s,x)=>s+x.qty,0);el.textContent=fa(count)}}
document.addEventListener('click',e=>{
 const add=e.target.closest('.add-cart');if(add){const id=Number(add.dataset.id);let cart=getCart(),found=cart.find(x=>x.id===id);if(found)found.qty++;else cart.push({id,name:add.dataset.name,price:Number(add.dataset.price),image:add.dataset.image,qty:1});setCart(cart);toast('محصول به سبد خرید اضافه شد.');}
 const wish=e.target.closest('.wish');if(wish){wish.classList.toggle('selected');wish.textContent=wish.classList.contains('selected')?'♥':'♡';}
});
updateCartCount();
// Accessible three-slide carousel: each slide owns its image and independent HTML copy.
(function initHeroSlider(){
 const root=document.querySelector('[data-slider]'); if(!root)return;
 const slides=[...root.querySelectorAll('[data-slide]')];
 const dots=[...root.querySelectorAll('.dot')];
 const previous=root.querySelector('.prev'), next=root.querySelector('.next'), pause=root.querySelector('.slider-pause');
 if(slides.length<2)return;
 let current=0, timer=null, paused=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
 const show=(index)=>{
   current=(index+slides.length)%slides.length;
   slides.forEach((item,i)=>{const active=i===current;item.classList.toggle('is-active',active);item.setAttribute('aria-hidden',String(!active));if(active)item.removeAttribute('inert');else item.setAttribute('inert','');});
   dots.forEach((dot,i)=>{const active=i===current;dot.classList.toggle('is-active',active);dot.setAttribute('aria-pressed',String(active));});
 };
 const stop=()=>{if(timer){window.clearInterval(timer);timer=null;}};
 const start=()=>{stop();if(!paused)timer=window.setInterval(()=>show(current+1),6500);};
 previous?.addEventListener('click',()=>{show(current-1);start();});
 next?.addEventListener('click',()=>{show(current+1);start();});
 dots.forEach((dot,i)=>dot.addEventListener('click',()=>{show(i);start();}));
 pause?.addEventListener('click',()=>{paused=!paused;pause.setAttribute('aria-pressed',String(paused));pause.textContent=paused?'▶':'Ⅱ';pause.setAttribute('aria-label',paused?'ادامه پخش خودکار اسلایدر':'توقف پخش خودکار اسلایدر');if(paused)stop();else start();});
 root.addEventListener('mouseenter',stop);root.addEventListener('mouseleave',start);
 root.addEventListener('focusin',stop);root.addEventListener('focusout',start);
 document.addEventListener('visibilitychange',()=>document.hidden?stop():start());
 show(0);start();
})();
