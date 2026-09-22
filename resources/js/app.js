import axios from 'axios';
window.axios=axios;window.axios.defaults.headers.common['X-Requested-With']='XMLHttpRequest';

const body=document.body;
const openLayer=(id)=>{document.getElementById(id)?.classList.add('is-open');body.classList.add('no-scroll')};
const closeLayers=()=>{document.querySelectorAll('.is-open[data-layer]').forEach(x=>x.classList.remove('is-open'));document.querySelectorAll('.nav-wrap.is-open').forEach(x=>x.classList.remove('is-open'));body.classList.remove('no-scroll')};
document.addEventListener('click',e=>{const trigger=e.target.closest('[data-open]');if(trigger){e.preventDefault();openLayer(trigger.dataset.open)}if(e.target.matches('[data-layer], [data-close]')||e.target.closest('[data-close]'))closeLayers()});
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeLayers()});

let closeTimer;document.querySelectorAll('.nav-wrap').forEach(item=>{item.addEventListener('mouseenter',()=>{clearTimeout(closeTimer);document.querySelectorAll('.nav-wrap.is-open').forEach(x=>x!==item&&x.classList.remove('is-open'));item.classList.add('is-open')});item.addEventListener('mouseleave',()=>{closeTimer=setTimeout(()=>item.classList.remove('is-open'),90)})});

const slides=[...document.querySelectorAll('.hero-slide')];let slide=0,timer;const go=n=>{if(!slides.length)return;slides[slide]?.classList.remove('is-active');document.querySelectorAll('.hero-dot')[slide]?.classList.remove('is-active');slide=(n+slides.length)%slides.length;slides[slide].classList.add('is-active');document.querySelectorAll('.hero-dot')[slide]?.classList.add('is-active')};if(slides.length>1){timer=setInterval(()=>go(slide+1),6500);document.querySelectorAll('.hero-dot').forEach((d,i)=>d.addEventListener('click',()=>{clearInterval(timer);go(i);timer=setInterval(()=>go(slide+1),6500)}))}

const observer=new IntersectionObserver(entries=>entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');observer.unobserve(e.target)}}),{threshold:.12});document.querySelectorAll('.reveal').forEach(el=>observer.observe(el));
document.querySelectorAll('[data-qty]').forEach(w=>{const input=w.querySelector('input');w.querySelector('[data-minus]')?.addEventListener('click',()=>input.value=Math.max(1,+input.value-1));w.querySelector('[data-plus]')?.addEventListener('click',()=>input.value=Math.min(10,+input.value+1))});
document.querySelectorAll('[data-thumb]').forEach(t=>t.addEventListener('click',()=>{const main=document.querySelector('[data-main-image]');if(main)main.src=t.dataset.thumb}));
setTimeout(()=>document.querySelectorAll('.toast').forEach(t=>t.remove()),5000);
document.querySelector('[data-admin-menu]')?.addEventListener('click',()=>document.querySelector('.admin-sidebar')?.classList.toggle('is-open'));
