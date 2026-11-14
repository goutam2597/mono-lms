window.monoToast=new class{constructor(){this.container=null,this.toasts=new Map,this.maxToasts=3}getOrCreateContainer(){return this.container&&document.body.contains(this.container)||(this.container=document.createElement(`div`),this.container.id=`mono-toast-container`,this.container.className=`fixed bottom-5 right-4 sm:bottom-6 sm:right-6 z-[99999] flex flex-col gap-2 pointer-events-none max-w-[calc(100vw-2rem)] sm:max-w-sm w-auto`,this.container.setAttribute(`aria-live`,`polite`),document.body.appendChild(this.container)),this.container}show(e={}){typeof e==`string`&&(e={message:e});let{type:t=`success`,message:n=``,action:r=null,duration:i=3200}=e;if(!n)return;let a=this.getOrCreateContainer(),o=a.querySelectorAll(`.mono-toast-item`);if(o.length>=this.maxToasts){let e=o[0].dataset.toastId;e&&this.dismiss(e)}let s=`toast-`+Math.random().toString(36).substring(2,9),c=document.createElement(`div`);c.className=`mono-toast-item pointer-events-auto bg-slate-900 text-white border border-slate-800 rounded-lg px-3.5 py-2.5 shadow-xl flex items-center gap-2.5 text-xs transition-all duration-200 transform translate-y-2 opacity-0`,c.dataset.toastId=s,c.setAttribute(`role`,`status`);let l=``;l=t===`success`?`
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            `:t===`error`?`
                <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            `:t===`warning`?`
                <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            `:`
                <svg class="w-4 h-4 text-sky-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
            `;let u=``;if(r&&r.label&&(u=`
                <a ${r.href?`href="${r.href}"`:`#`} class="mono-toast-action font-semibold text-emerald-400 hover:text-emerald-300 underline underline-offset-2 ml-auto shrink-0 transition-colors">
                    ${r.label}
                </a>
            `),c.innerHTML=`
            ${l}
            <span class="text-slate-100 font-medium leading-snug break-words">${n}</span>
            ${u}
            <button type="button" class="mono-toast-close text-slate-400 hover:text-white p-0.5 -mr-1 transition-colors shrink-0 cursor-pointer ml-1 text-base leading-none" aria-label="Dismiss">&times;</button>
        `,a.appendChild(c),r&&typeof r.onClick==`function`){let e=c.querySelector(`.mono-toast-action`);e&&e.addEventListener(`click`,e=>{r.onClick(e),this.dismiss(s)})}c.querySelector(`.mono-toast-close`)?.addEventListener(`click`,()=>{this.dismiss(s)}),requestAnimationFrame(()=>{c.classList.remove(`translate-y-2`,`opacity-0`),c.classList.add(`translate-y-0`,`opacity-100`)});let d=setTimeout(()=>{this.dismiss(s)},i);return this.toasts.set(s,{el:c,timer:d}),s}dismiss(e){let t=this.toasts.get(e);if(!t)return;let{el:n,timer:r}=t;clearTimeout(r),n.classList.add(`opacity-0`,`translate-y-1`),n.style.pointerEvents=`none`,setTimeout(()=>{n&&n.parentNode&&n.parentNode.removeChild(n),this.toasts.delete(e)},180)}success(e,t={}){return this.show({...t,type:`success`,message:e})}error(e,t={}){return this.show({...t,type:`error`,message:e})}info(e,t={}){return this.show({...t,type:`info`,message:e})}warning(e,t={}){return this.show({...t,type:`warning`,message:e})}},window.showToast=function(e,t=`success`,n={}){return window.monoToast.show({message:e,type:t,...n})},document.addEventListener(`DOMContentLoaded`,()=>{document.querySelectorAll(`[data-flash-toast]`).forEach(e=>{let t=e.getAttribute(`data-type`)||`success`,n=e.getAttribute(`data-message`)||e.textContent.trim();n&&(window.monoToast.show({type:t,message:n}),e.remove())})}),window.monoToast;function e(){let e=document.getElementById(`global-search-toggle`),t=document.getElementById(`global-search-bar`),n=document.getElementById(`global-search-input`),r=document.getElementById(`global-search-cancel`),i=document.getElementById(`global-search-backdrop`),a=document.getElementById(`global-search-icon-open`),o=document.getElementById(`global-search-icon-close`);if(!e||!t)return;function s(){t.classList.remove(`hidden`),i&&i.classList.remove(`hidden`),a&&a.classList.add(`hidden`),o&&o.classList.remove(`hidden`),e.setAttribute(`aria-expanded`,`true`),e.classList.add(`bg-slate-100`),setTimeout(()=>{n&&(n.focus(),n.select())},50)}function c(){t.classList.add(`hidden`),i&&i.classList.add(`hidden`),a&&a.classList.remove(`hidden`),o&&o.classList.add(`hidden`),e.setAttribute(`aria-expanded`,`false`),e.classList.remove(`bg-slate-100`)}function l(){t.classList.contains(`hidden`)?s():c()}e.addEventListener(`click`,e=>{e.preventDefault(),e.stopPropagation(),l()}),r&&r.addEventListener(`click`,e=>{e.preventDefault(),c()}),i&&i.addEventListener(`click`,()=>{c()}),document.addEventListener(`keydown`,n=>{if(n.key===`Escape`&&!t.classList.contains(`hidden`))c(),e.focus();else if(n.key===`/`&&t.classList.contains(`hidden`)){let e=document.activeElement?document.activeElement.tagName.toLowerCase():``;e!==`input`&&e!==`textarea`&&e!==`select`&&!document.activeElement?.isContentEditable&&(n.preventDefault(),s())}}),document.addEventListener(`click`,n=>{t.classList.contains(`hidden`)||!t.contains(n.target)&&!e.contains(n.target)&&c()})}document.addEventListener(`DOMContentLoaded`,e);var t=typeof window<`u`&&window.searchSuggestI18n||{};function n(e,n){let r=typeof window<`u`&&window.searchSuggestI18n||t;return typeof r[e]==`string`&&r[e]?r[e]:n}var r=`
.ss-drop {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 20px 40px -8px rgba(15, 23, 42, 0.16), 0 8px 16px -4px rgba(15, 23, 42, 0.08);
    display: none;
    z-index: 99999;
    max-height: 490px;
    overflow-y: auto;
    overscroll-behavior: contain;
    animation: ss-in .14s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes ss-in {
    from { opacity: 0; transform: translateY(-4px) scale(0.99); }
    to { opacity: 1; transform: none; }
}
.ss-drop.ss-open { display: block; }
.ss-section {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #94a3b8;
    padding: 10px 16px 4px;
}
.ss-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 16px;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    transition: background-color .12s ease;
}
.ss-item:hover, .ss-item.ss-active {
    background: #f8fafc;
}
.ss-thumb {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
}
.ss-thumb-ph {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    background: #f1f5f9;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 16px;
}
.ss-avatar {
    width: 38px;
    height: 38px;
    border-radius: 9999px;
    object-fit: cover;
    flex-shrink: 0;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
}
.ss-avatar-ph {
    width: 38px;
    height: 38px;
    border-radius: 9999px;
    background: var(--theme-primary, #344c3d);
    color: #ffffff;
    font-weight: 700;
    font-size: 13px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ss-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #f1f5f9;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
}
.ss-body {
    flex: 1;
    min-width: 0;
}
.ss-name {
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.35;
}
.ss-meta {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ss-badge {
    display: inline-block;
    padding: 1px 5px;
    font-size: 9.5px;
    font-weight: 600;
    background: #f1f5f9;
    color: #475569;
    border-radius: 4px;
}
.ss-price {
    font-size: 13px;
    font-weight: 700;
    color: var(--theme-primary, #344c3d);
    white-space: nowrap;
    flex-shrink: 0;
    text-align: right;
}
.ss-old {
    font-size: 10.5px;
    color: #94a3b8;
    text-decoration: line-through;
    margin-right: 4px;
    font-weight: 500;
}
.ss-sep {
    border: 0;
    border-top: 1px solid #f1f5f9;
    margin: 4px 0;
}
.ss-all {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 11px 16px;
    font-size: 12.5px;
    font-weight: 700;
    color: var(--theme-primary, #344c3d);
    border-top: 1px solid #f1f5f9;
    text-decoration: none;
    transition: background-color .12s ease;
}
.ss-all:hover, .ss-all.ss-active {
    background: #f8fafc;
}
.ss-empty {
    text-align: center;
    padding: 24px 16px;
    color: #94a3b8;
    font-size: 13px;
}
mark.ss-hl {
    background: rgba(52, 76, 61, 0.16);
    color: inherit;
    border-radius: 2px;
    padding: 0 2px;
    font-style: normal;
}
`;function i(){if(typeof document>`u`||document.getElementById(`ss-style`))return;let e=document.createElement(`style`);e.id=`ss-style`,e.textContent=r,document.head.appendChild(e)}function a(e){return String(e??``).replace(/&/g,`&amp;`).replace(/</g,`&lt;`).replace(/>/g,`&gt;`).replace(/"/g,`&quot;`)}function o(e,t){if(!t)return a(e);let n=RegExp(`(`+t.replace(/[.*+?^${}()|[\]\\]/g,`\\$&`)+`)`,`gi`);return a(e).replace(n,`<mark class="ss-hl">$1</mark>`)}function s(e){if(!e||e._ssAttached)return;e._ssAttached=!0;let t=e.getAttribute(`data-suggest`);if(!t)return;let r=e.getAttribute(`data-suggest-container`),i=r?e.closest(r):null;i||=e.closest(`form`)||e.parentElement,window.getComputedStyle(i).position===`static`&&(i.style.position=`relative`);let s=document.createElement(`div`);s.className=`ss-drop`,i.appendChild(s);let c=null,l=``,u=[],d=-1;function f(){s.classList.add(`ss-open`)}function p(){s.classList.remove(`ss-open`),d=-1}function m(e){u.forEach((t,n)=>t.classList.toggle(`ss-active`,n===e)),d=e,e>=0&&u[e]&&u[e].scrollIntoView({block:`nearest`})}function h(e,t,n){let r=document.createElement(e);return r.className=t,n.appendChild(r),r}function g(e,t){s.innerHTML=``,u=[];let r=e.courses&&e.courses.length,i=e.categories&&e.categories.length,c=e.instructors&&e.instructors.length;if(!r&&!i&&!c){s.innerHTML=`<div class="ss-empty">${a(n(`no_results_for`,`No results for`))} &ldquo;<b>${a(t)}</b>&rdquo;</div>`,f();return}function l(e){h(`div`,`ss-section`,s).textContent=e}function d(){h(`hr`,`ss-sep`,s)}function p(e,t){let n=h(`a`,`ss-item`,s);n.href=t,n.innerHTML=e,u.push(n)}r&&(l(n(`courses`,`Courses`)),e.courses.forEach(e=>{let n=e.image?`<img class="ss-thumb" src="${a(e.image)}" alt="" loading="lazy">`:`<div class="ss-thumb-ph"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>`,r=(e.old_price?`<span class="ss-old">${a(e.old_price)}</span>`:``)+`<span class="${e.price===`Free`?`text-emerald-600 font-bold`:``}">${a(e.price)}</span>`,i=[];e.category&&i.push(`<span class="ss-badge">${a(e.category)}</span>`),e.instructor&&i.push(`<span>by ${a(e.instructor)}</span>`),p(n+`<div class="ss-body"><div class="ss-name">${o(e.name,t)}</div>`+(i.length?`<div class="ss-meta">${i.join(``)}</div>`:``)+`</div><div class="ss-price">${r}</div>`,e.url)})),i&&(r&&d(),l(n(`categories`,`Categories`)),e.categories.forEach(e=>{p(`<div class="ss-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></div><div class="ss-body"><div class="ss-name">${o(e.name,t)}</div><div class="ss-meta">${a(e.count)} ${a(n(`courses_count`,`courses`))}</div></div>`,e.url)})),c&&((r||i)&&d(),l(n(`instructors`,`Instructors`)),e.instructors.forEach(e=>{p((e.avatar?`<img class="ss-avatar" src="${a(e.avatar)}" alt="" loading="lazy">`:`<div class="ss-avatar-ph">${a(e.name.charAt(0).toUpperCase())}</div>`)+`<div class="ss-body"><div class="ss-name">${o(e.name,t)}</div><div class="ss-meta"><span>${a(e.designation)}</span> &bull; <span>${a(e.count)} ${a(n(`courses_count`,`courses`))}</span></div></div>`,e.url)}));let m=e.see_all_url||`courses?q=`+encodeURIComponent(t),g=h(`a`,`ss-all`,s);g.href=m,g.innerHTML=`<span>${a(n(`see_all_results`,`See all results for`))} &ldquo;<b>${a(t)}</b>&rdquo;</span> <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>`,u.push(g),f()}function _(e){let n=t+(t.indexOf(`?`)>=0?`&`:`?`)+`q=`+encodeURIComponent(e);fetch(n,{headers:{Accept:`application/json`}}).then(e=>e.ok?e.json():null).then(t=>{t&&(l=e,g(t,e))}).catch(()=>{})}e.addEventListener(`input`,function(){clearTimeout(c);let e=this.value.trim();e.length<2?p():c=setTimeout(()=>_(e),250)}),e.addEventListener(`keydown`,function(e){s.classList.contains(`ss-open`)&&(e.key===`ArrowDown`?(e.preventDefault(),m(Math.min(d+1,u.length-1))):e.key===`ArrowUp`?(e.preventDefault(),m(d<=0?-1:d-1)):e.key===`Enter`&&d>=0&&u[d]?(e.preventDefault(),u[d].click()):e.key===`Escape`&&p())}),e.addEventListener(`focus`,function(){this.value.trim().length>=2&&l===this.value.trim()&&f()}),document.addEventListener(`click`,function(t){!i.contains(t.target)&&t.target!==e&&p()})}function c(){i(),typeof document<`u`&&document.querySelectorAll(`input[data-suggest]`).forEach(s)}typeof window<`u`&&(window.initSearchSuggest=c,window.attachSearchSuggest=s),typeof document<`u`&&(document.readyState===`loading`?document.addEventListener(`DOMContentLoaded`,c):c()),(function(){var e=null;try{var t=Object.getOwnPropertyDescriptor(HTMLSelectElement.prototype,`value`);if(t&&!HTMLSelectElement.prototype._mselectWrapped){HTMLSelectElement.prototype._mselectWrapped=!0;var n=t.set;Object.defineProperty(HTMLSelectElement.prototype,"value",{set:function(e){n.call(this,e),typeof this._mselectSync==`function`&&this._mselectSync()},get:t.get,configurable:!0})}}catch{}function r(){if(e){var t=e;e=null,t.wrap.classList.remove(`open`),t.btn.setAttribute(`aria-expanded`,`false`),t.list.classList.remove(`show`),setTimeout(function(){t.list&&t.list.parentNode&&t.list.remove()},180),document.removeEventListener(`click`,o,!0),window.removeEventListener(`scroll`,a,!0),window.removeEventListener(`resize`,r)}}function i(){if(e){var t=e.btn.getBoundingClientRect(),n=e.list,r=parseInt(n.style.maxHeight,10)||260,i=Math.min(n.offsetHeight||0,r),a=parseInt(e.sel.getAttribute(`data-mselect-min-width`),10)||0;n.style.width=`auto`;var o=n.offsetWidth;n.scrollHeight>n.clientHeight+1&&(o+=8);var s=Math.max(t.width,a,o);!e.sel.classList.contains(`w-full`)&&s<130&&(s=Math.max(s,130));var c=window.innerWidth-16;s>c&&(s=c),window.innerHeight-t.bottom<i+8&&t.top>i+8?n.style.top=t.top-i-4+`px`:n.style.top=t.bottom+4+`px`;var l=Math.min(t.left,Math.max(8,window.innerWidth-s-8));if(n.style.left=l+`px`,n.style.width=s+`px`,document.documentElement.dir===`rtl`){var u=t.left+t.width-window.innerWidth;u>0&&(n.style.left=Math.max(8,t.left-u-8)+`px`)}}}function a(t){e&&(e.list.contains(t.target)||i())}function o(t){e&&!e.list.contains(t.target)&&!e.btn.contains(t.target)&&r()}function s(e,t){var n=document.createElement(`div`);n.className=`mselect-list`+(e.getAttribute(`data-mselect-list-class`)?` `+e.getAttribute(`data-mselect-list-class`):``),e.getAttribute(`data-mselect-max-height`)&&(n.style.maxHeight=e.getAttribute(`data-mselect-max-height`)+`px`);var i=[],a=null,o=null;function s(s){if(!s.disabled||s.value){var c=document.createElement(`div`);c.className=`mselect-opt`+(s.selected?` is-selected`:``),c.textContent=s.textContent,c.addEventListener(`click`,function(){e.value=s.value,t(),e.dispatchEvent(new Event(`change`,{bubbles:!0})),e.dispatchEvent(new Event(`input`,{bubbles:!0})),r()}),n.insertBefore(c,o),i.push({el:c,text:s.textContent.toLowerCase(),group:a})}}function c(){Array.prototype.forEach.call(n.querySelectorAll(`.mselect-opt,.mselect-group`),function(e){e.remove()}),i.length=0,a=null,Array.prototype.forEach.call(e.children,function(e){if(e.tagName===`OPTGROUP`){var t=document.createElement(`div`);t.className=`mselect-group`,t.textContent=e.label,n.insertBefore(t,o),a=t,Array.prototype.forEach.call(e.children,s)}else e.tagName===`OPTION`&&s(e)})}if(c(),i.length>=6&&!e.hasAttribute(`data-mselect-no-search`)){var l=document.createElement(`div`);l.className=`mselect-search`;var u=document.createElement(`input`);u.type=`text`,u.placeholder=e.getAttribute(`data-mselect-search`)||`Search…`,l.appendChild(u),n.insertBefore(l,n.firstChild);var d=document.createElement(`div`);d.className=`mselect-empty`,d.textContent=`No results found`,n.appendChild(d),o=d;function t(e){var t=0;i.forEach(function(n){var r=!e||n.text.indexOf(e)!==-1;n.el.style.display=r?``:`none`,r&&t++}),Array.prototype.forEach.call(n.querySelectorAll(`.mselect-group`),function(e){var t=i.some(function(t){return t.group===e&&t.el.style.display!==`none`});e.style.display=t?``:`none`}),d.style.display=t?`none`:`block`}u.addEventListener(`input`,function(){t(u.value.trim().toLowerCase())}),u.addEventListener(`keydown`,function(e){if(e.key===`Escape`&&r(),e.key===`Enter`){e.preventDefault();var t=i.filter(function(e){return e.el.style.display!==`none`})[0];t&&t.el.click()}}),setTimeout(function(){u.focus()},10)}return n}function c(t){if(!t||t.dataset.mselectDone||t.hasAttribute(`data-no-mselect`)||t.closest(`.mono-select`))return;t.dataset.mselectDone=`1`;var n=document.createElement(`div`);n.className=`mselect`;var c=/(?:^|\s)w-/.test(t.className||``);t.classList.contains(`w-full`)?n.classList.add(`w-full`):c||n.classList.add(`w-auto`,`flex-none`),(t.className||``).split(/\s+/).forEach(function(e){/^(w-|max-w-|min-w-|flex-|shrink-|grow-|h-|self-)/.test(e)&&n.classList.add(e)}),t.parentNode.insertBefore(n,t),n.appendChild(t);var l=document.createElement(`button`);if(l.type=`button`,l.className=`mselect-btn `+(t.className?t.className:``),l.setAttribute(`aria-haspopup`,`listbox`),l.setAttribute(`aria-expanded`,`false`),t.id){var u=document.querySelector(`label[for="`+t.id.replace(/(["\\])/g,`\\$1`)+`"]`);u&&(u.id||=t.id+`-label`,l.setAttribute(`aria-labelledby`,u.id),u.addEventListener(`click`,function(e){e.preventDefault(),l.focus()}))}t.hasAttribute(`aria-label`)&&l.setAttribute(`aria-label`,t.getAttribute(`aria-label`)),t.disabled&&(l.disabled=!0);var d=document.createElement(`span`);d.className=`mselect-label`;var f=document.createElement(`span`);f.className=`mselect-chev`,f.innerHTML=`<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>`,l.appendChild(d),l.appendChild(f),n.appendChild(l);function p(){var e=t.options[t.selectedIndex];d.textContent=e?e.getAttribute(`data-trigger`)||e.textContent:t.getAttribute(`placeholder`)||`Select…`,l.title=e?e.textContent:``}t._mselectSync=p,p(),t.addEventListener(`change`,p),window.MutationObserver&&new MutationObserver(function(){p()}).observe(t,{childList:!0,subtree:!0,characterData:!0}),l.addEventListener(`click`,function(c){if(c.preventDefault(),c.stopPropagation(),e&&e.btn===l)r();else{r();var u=s(t,p);document.body.appendChild(u),l.setAttribute(`aria-expanded`,`true`),e={list:u,wrap:n,btn:l,sel:t},i(),requestAnimationFrame(function(){u.classList.add(`show`)}),n.classList.add(`open`),document.addEventListener(`click`,o,!0),window.addEventListener(`scroll`,a,!0),window.addEventListener(`resize`,r)}}),l.addEventListener(`keydown`,function(e){e.key===`Escape`&&r(),(e.key===`ArrowDown`||e.key===`Enter`)&&(e.preventDefault(),l.click())})}if(window.mselectEnhance=function(e){(e||document).querySelectorAll(`select:not([data-no-mselect])`).forEach(c)},document.readyState===`loading`?document.addEventListener(`DOMContentLoaded`,function(){window.mselectEnhance(document)}):window.mselectEnhance(document),window.MutationObserver){var l=new MutationObserver(function(e){for(var t=0;t<e.length;t++)if(e[t].addedNodes.length){window.mselectEnhance(document);break}});document.body?l.observe(document.body,{childList:!0,subtree:!0}):document.addEventListener(`DOMContentLoaded`,function(){l.observe(document.body,{childList:!0,subtree:!0})})}})();var l=[`bg-[#f4f6f9]`,`text-[#344c3d]`,`font-semibold`];function u(e=document){e.querySelectorAll(`[data-mono-select]:not([data-ms-ready])`).forEach(e=>{e.dataset.msReady=`1`;let t=e.querySelector(`[data-ms-trigger]`),n=e.querySelector(`[data-ms-panel]`),r=e.querySelector(`[data-ms-input]`),i=e.querySelector(`[data-ms-label]`),a=e.querySelector(`[data-ms-search]`),o=e.querySelector(`[data-ms-chevron]`),s=[...e.querySelectorAll(`[data-ms-option]`)],c=e.querySelector(`[data-ms-empty]`),u=``,d=null,f=-1,p=()=>s.filter(e=>!e.classList.contains(`hidden`)),m=e=>{let t=(e||``).trim().toLowerCase(),n=!1;s.forEach(e=>{let r=!t||(e.dataset.label||``).includes(t);e.classList.toggle(`hidden`,!r),r&&(n=!0)}),c?.classList.toggle(`hidden`,n),f=-1},h=e=>{let t=p();t.length&&(f=(e%t.length+t.length)%t.length,t.forEach(e=>e.classList.remove(...l)),t[f].classList.add(...l),t[f].scrollIntoView({block:`nearest`}))},g=()=>{s.forEach(e=>e.classList.remove(...l)),f=-1},_=()=>{document.querySelectorAll(`[data-mono-select][data-ms-open]`).forEach(t=>{t!==e&&t.dispatchEvent(new CustomEvent(`ms-close`))}),n.classList.remove(`hidden`),o?.classList.add(`rotate-180`),e.dataset.msOpen=`1`,g(),a&&(a.value=``,m(``),a.focus())},v=()=>{n.classList.add(`hidden`),o?.classList.remove(`rotate-180`),delete e.dataset.msOpen,g(),a&&(a.value=``,m(``)),u=``},y=t=>{t&&(r.value=t.getAttribute(`value`),i.textContent=t.querySelector(`[data-ms-option-label]`)?.textContent??t.textContent.trim(),i.classList.remove(`text-gray-400`),s.forEach(e=>e.classList.remove(`text-[#344c3d]`,`text-[#ff4667]`,`font-semibold`)),t.classList.add(`text-[#344c3d]`,`font-semibold`),v(),e.dispatchEvent(new Event(`change`,{bubbles:!0})))},b=e=>{u+=e.toLowerCase(),clearTimeout(d),d=setTimeout(()=>u=``,600);let t=p(),n=t.find(e=>(e.dataset.label||``).startsWith(u))??t.find(e=>(e.dataset.label||``).includes(u));n&&h(t.indexOf(n))};t.addEventListener(`click`,e=>{e.stopPropagation(),n.classList.contains(`hidden`)?_():v()}),e.addEventListener(`ms-close`,v),document.addEventListener(`click`,t=>{e.contains(t.target)||v()}),s.forEach(e=>e.addEventListener(`click`,()=>y(e))),a&&(a.addEventListener(`input`,()=>m(a.value)),a.addEventListener(`keydown`,e=>{e.key===`Enter`&&(e.preventDefault(),y(p().find(e=>!e.classList.contains(`hidden`))))})),e.addEventListener(`keydown`,e=>{let t=e.target===a;if(n.classList.contains(`hidden`))[`Enter`,` `,`ArrowDown`].includes(e.key)&&(e.preventDefault(),_());else switch(e.key){case`ArrowDown`:e.preventDefault(),h(f+1);break;case`ArrowUp`:e.preventDefault(),h(f-1);break;case`Enter`:t||(e.preventDefault(),y(p()[f]??p()[0]));break;case`Escape`:e.preventDefault(),v();break;default:t||e.key.length!==1||e.ctrlKey||e.metaKey||e.altKey||b(e.key)}}),e.hasAttribute(`data-ms-autosubmit`)&&e.addEventListener(`change`,()=>e.closest(`form`)?.requestSubmit())})}document.addEventListener(`DOMContentLoaded`,()=>u()),window.initMonoSelects=u;var d=8e3;function f(){let e=document.querySelector(`[data-hero-showcase]`);if(!e||e.dataset.heroReady)return;e.dataset.heroReady=`1`;let t=[...e.querySelectorAll(`[data-hero-slide]`)];if(!t.length)return;let n=window.matchMedia(`(prefers-reduced-motion: reduce)`).matches,r=0;t.forEach((e,t)=>e.classList.toggle(`is-on`,t===0)),n||t.length<=1||setInterval(()=>{r++,t.forEach((e,n)=>e.classList.toggle(`is-on`,n===r%t.length))},d)}document.addEventListener(`DOMContentLoaded`,f),window.initHeroShowcase=f;function p(e){if(!e||e.dataset.sliderReady===`1`)return;e.dataset.sliderReady=`1`;let t=e.querySelector(`[data-slider-container]`),n=e.querySelector(`[data-slider-track]`),r=e.querySelector(`[data-slider-prev]`),i=e.querySelector(`[data-slider-next]`),a=e.parentElement?e.parentElement.querySelector(`[data-slider-dots]`):e.querySelector(`[data-slider-dots]`);if(!t||!n)return;let o=Array.from(n.querySelectorAll(`[data-slider-card]`)),s=o.length;if(s===0)return;let c=e.hasAttribute(`data-trending-slider`),l=c?4:5,u=0,d=!1,f=0,p=24,m=g(),h=Math.max(1,Math.ceil(s/m));function g(){let e=window.innerWidth;return e>=1024?l:e>=768?Math.min(3,l):e>=640?Math.min(2,l):1}function _(){return window.innerWidth>=640?24:20}let v=Math.min(s,Math.max(m,l)),y=o.slice(-v).map(e=>{let t=e.cloneNode(!0);return t.setAttribute(`data-slider-clone`,`prefix`),t.setAttribute(`aria-hidden`,`true`),t}),b=o.slice(0,v).map(e=>{let t=e.cloneNode(!0);return t.setAttribute(`data-slider-clone`,`suffix`),t.setAttribute(`aria-hidden`,`true`),t});y.forEach(e=>n.insertBefore(e,n.firstChild)),b.forEach(e=>n.appendChild(e)),u=v;function x(){m=g(),p=_();let e=t.clientWidth;f=window.innerWidth<640?Math.min(c?300:270,Math.floor(e*.85)):(e-(m-1)*p)/m,n.querySelectorAll(`[data-slider-card]`).forEach(e=>{e.style.width=`${f}px`,e.style.flexShrink=`0`}),h=Math.max(1,Math.ceil(s/m)),T(),S(u,!1)}function S(e,t=!0){u=e;let r=f+p,i=-(u*r);t?(d=!0,n.style.transition=`transform 450ms cubic-bezier(0.22, 1, 0.36, 1)`):n.style.transition=`none`,n.style.transform=`translateX(${i}px)`,E()}n.addEventListener(`transitionend`,()=>{d=!1;let e=f+p;u>=s+v?(u-=s,n.style.transition=`none`,n.style.transform=`translateX(${-(u*e)}px)`):u<v&&(u+=s,n.style.transition=`none`,n.style.transform=`translateX(${-(u*e)}px)`),E()});function C(){d||S(u+(m>1?m:1),!0)}function w(){d||S(u-(m>1?m:1),!0)}i&&i.addEventListener(`click`,C),r&&r.addEventListener(`click`,w);function T(){if(a){a.innerHTML=``;for(let e=0;e<h;e++){let t=document.createElement(`button`);t.type=`button`,t.className=`slider-dot transition-all duration-300 rounded-full cursor-pointer focus:outline-none`,t.setAttribute(`aria-label`,`Go to slide page ${e+1}`),t.dataset.page=String(e),t.addEventListener(`click`,()=>{d||S(v+e*m,!0)}),a.appendChild(t)}E()}}function E(){if(!a)return;let e=a.querySelectorAll(`.slider-dot`);if(!e.length)return;let t=(u-v+s*100)%s,n=Math.min(h-1,Math.floor(t/m));e.forEach((e,t)=>{t===n?(e.className=`slider-dot transition-all duration-300 w-8 h-2.5 rounded-full bg-[#344c3d] shadow-sm cursor-pointer`,e.setAttribute(`aria-current`,`true`)):(e.className=`slider-dot transition-all duration-300 w-2.5 h-2.5 rounded-full bg-[#DCE7DA] hover:bg-[#8EA58C] cursor-pointer`,e.removeAttribute(`aria-current`))})}let D=!1,O=!1,k=!1,A=0;function j(e){e.button!==void 0&&e.button!==0||e.target.closest(`button, [data-wishlist-toggle], a, input, select`)||(D=!0,O=!1,k=!1,A=e.type.includes(`mouse`)?e.pageX:e.touches[0].clientX,n.style.transition=`none`,n.style.cursor=`grabbing`)}function M(e){if(!D)return;let t=(e.type.includes(`mouse`)?e.pageX:e.touches[0].clientX)-A;if(Math.abs(t)>6&&(O=!0,k=!0),O){let e=f+p,r=-(u*e);n.style.transform=`translateX(${r+t}px)`}}function N(e){if(!D||(D=!1,n.style.cursor=``,!O))return;let t=(e.type.includes(`mouse`)?e.pageX:e.changedTouches?e.changedTouches[0].clientX:A)-A,r=f*.22;t<-r?C():t>r?w():S(u,!0),setTimeout(()=>{O=!1,k=!1},80)}n.addEventListener(`click`,e=>{e.target.closest(`button, [data-wishlist-toggle]`)||k&&(e.preventDefault(),e.stopPropagation())},!0),t.addEventListener(`mousedown`,j),window.addEventListener(`mousemove`,M),window.addEventListener(`mouseup`,N),t.addEventListener(`touchstart`,j,{passive:!0}),t.addEventListener(`touchmove`,M,{passive:!0}),t.addEventListener(`touchend`,N),e.addEventListener(`keydown`,e=>{e.key===`ArrowLeft`&&w(),e.key===`ArrowRight`&&C()});let P;window.addEventListener(`resize`,()=>{clearTimeout(P),P=setTimeout(x,100)}),x()}function m(){document.querySelectorAll(`[data-category-slider], [data-trending-slider]`).forEach(p)}document.addEventListener(`DOMContentLoaded`,m);function h(){let e=document.querySelector(`[data-featured-tabs]`),t=document.querySelector(`[data-featured-grid]`),n=document.querySelector(`[data-featured-empty]`),r=document.querySelector(`[data-featured-reset]`);if(!e||!t)return;let i=Array.from(e.querySelectorAll(`[data-filter]`)),a=Array.from(t.querySelectorAll(`[data-tags]`)),o=[`border-[#344c3d]`,`text-[#344c3d]`,`bg-[#dce7da]/40`,`font-semibold`,`shadow-xs`],s=[`border-transparent`,`text-[#191919]`,`hover:bg-white`,`hover:shadow-xs`,`font-medium`];function c(e){let t=0;i.forEach(t=>{t.dataset.filter===e?(t.classList.remove(...s),t.classList.add(...o),t.setAttribute(`aria-pressed`,`true`)):(t.classList.remove(...o),t.classList.add(...s),t.setAttribute(`aria-pressed`,`false`))}),a.forEach(n=>{let r=(n.dataset.tags||``).split(/\s+/).filter(Boolean);e===`all`||r.includes(e)?(n.classList.remove(`hidden`),t++):n.classList.add(`hidden`)}),n&&(t===0?n.classList.remove(`hidden`):n.classList.add(`hidden`))}i.forEach(e=>{e.addEventListener(`click`,t=>{t.preventDefault(),c(e.dataset.filter)})}),r&&r.addEventListener(`click`,e=>{e.preventDefault(),c(`all`)})}document.addEventListener(`DOMContentLoaded`,h);function g(e,t=`success`,n={}){if(window.monoToast)return window.monoToast.show({message:e,type:t,...n});if(window.showToast)return window.showToast(e,t,n)}function _(e,t){e.setAttribute(`data-saved`,t?`1`:`0`),e.setAttribute(`title`,t?`Remove from Saved Courses`:`Save Course`),e.setAttribute(`aria-label`,t?`Remove from Saved Courses`:`Save Course`);let n=e.querySelector(`svg`);n&&(t?(n.setAttribute(`fill`,`currentColor`),n.classList.remove(`fill-none`,`text-slate-600`,`text-slate-500`,`text-gray-500`),n.classList.add(`fill-rose-500`,`text-rose-500`,`stroke-rose-500`),e.classList.add(`text-rose-500`),e.classList.remove(`text-slate-600`,`text-gray-500`,`text-slate-700`)):(n.setAttribute(`fill`,`none`),n.classList.remove(`fill-rose-500`,`stroke-rose-500`,`text-rose-500`),n.classList.add(`fill-none`,`text-slate-600`),e.classList.remove(`text-rose-500`),e.classList.add(`text-slate-600`)));let r=e.querySelector(`[data-wishlist-label]`);r&&(r.textContent=t?`Saved in Wishlist`:`Save to Wishlist`)}async function v(e,t){if(t&&(t.preventDefault(),t.stopPropagation()),!e||e.hasAttribute(`data-loading`))return;let n=e.getAttribute(`data-wishlist-toggle`);if(e.getAttribute(`data-guest`)===`1`||n===`/login`||n?.endsWith(`/login`)||!n){g(`Please sign in to save courses.`,`warning`,{action:{label:`Sign In`,href:`/login`}}),setTimeout(()=>{window.location.href=`/login`},700);return}e.setAttribute(`data-loading`,`true`);let r=e.getAttribute(`data-saved`)===`1`,i=!r,a=document.querySelectorAll(`[data-wishlist-toggle="${CSS.escape(n)}"]`);a.forEach(e=>_(e,i));let o=document.querySelector(`meta[name="csrf-token"]`)?.getAttribute(`content`)||``;try{let e=await fetch(n,{method:`POST`,headers:{"Content-Type":`application/json`,Accept:`application/json`,"X-CSRF-TOKEN":o,"X-Requested-With":`XMLHttpRequest`},body:JSON.stringify({})});if(e.status===401){let t=await e.json().catch(()=>({}));g(`Please sign in to save courses.`,`warning`,{action:{label:`Sign In`,href:t.redirect||`/login`}}),setTimeout(()=>{window.location.href=t.redirect||`/login`},700);return}if(!e.ok)throw Error(`Failed to update wishlist`);let t=await e.json();a.forEach(e=>_(e,!!t.saved)),t.count!==void 0&&document.querySelectorAll(`[data-wishlist-count]`).forEach(e=>{e.textContent=t.count>0?t.count:``,e.classList.toggle(`hidden`,t.count===0)}),t.saved?g(t.message||`Course saved to your list.`,`success`,{action:{label:`View`,href:`/student/saved-courses`}}):g(t.message||`Course removed from saved courses.`,`info`)}catch{a.forEach(e=>_(e,r)),g(`Could not update saved status. Please try again.`,`error`)}finally{e.removeAttribute(`data-loading`)}}document.addEventListener(`click`,e=>{let t=e.target.closest(`[data-wishlist-toggle]`);t&&v(t,e)});var y=window.matchMedia(`(prefers-reduced-motion: reduce)`).matches;function b(e){let t=parseInt(e.dataset.countup,10)||0,n=e.dataset.suffix??``;if(y||t===0){e.textContent=t.toLocaleString()+n;return}let r=performance.now(),i=a=>{let o=Math.min((a-r)/1200,1),s=1-(1-o)**3;e.textContent=Math.round(t*s).toLocaleString()+n,o<1&&requestAnimationFrame(i)};requestAnimationFrame(i)}document.addEventListener(`DOMContentLoaded`,()=>{let e=document.querySelectorAll(`[data-countup]`);if(!e.length)return;if(!(`IntersectionObserver`in window)){e.forEach(b);return}let t=new IntersectionObserver(e=>{e.forEach(e=>{e.isIntersecting&&(b(e.target),t.unobserve(e.target))})},{threshold:.4});e.forEach(e=>t.observe(e))});