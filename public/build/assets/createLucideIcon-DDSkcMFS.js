import{r,j as E}from"./app-Bq_TD4f7.js";import{M as B,i as K,u as z,P as D,a as H,b as Z,L as _}from"./proxy-CasmINv1.js";function b(e,t){if(typeof e=="function")return e(t);e!=null&&(e.current=t)}function F(...e){return t=>{let n=!1;const o=e.map(i=>{const s=b(i,t);return!n&&typeof s=="function"&&(n=!0),s});if(n)return()=>{for(let i=0;i<o.length;i++){const s=o[i];typeof s=="function"?s():b(e[i],null)}}}}function G(...e){return r.useCallback(F(...e),e)}class N extends r.Component{getSnapshotBeforeUpdate(t){const n=this.props.childRef.current;if(n&&t.isPresent&&!this.props.isPresent){const o=n.offsetParent,i=K(o)&&o.offsetWidth||0,s=this.props.sizeRef.current;s.height=n.offsetHeight||0,s.width=n.offsetWidth||0,s.top=n.offsetTop,s.left=n.offsetLeft,s.right=i-s.width-s.left}return null}componentDidUpdate(){}render(){return this.props.children}}function O({children:e,isPresent:t,anchorX:n,root:o}){var p;const i=r.useId(),s=r.useRef(null),f=r.useRef({width:0,height:0,top:0,left:0,right:0}),{nonce:m}=r.useContext(B),w=((p=e.props)==null?void 0:p.ref)??(e==null?void 0:e.ref),u=G(s,w);return r.useInsertionEffect(()=>{const{width:a,height:l,top:d,left:C,right:g}=f.current;if(t||!s.current||!a||!l)return;const P=n==="left"?`left: ${C}`:`right: ${g}`;s.current.dataset.motionPopId=i;const x=document.createElement("style");m&&(x.nonce=m);const L=o??document.head;return L.appendChild(x),x.sheet&&x.sheet.insertRule(`
          [data-motion-pop-id="${i}"] {
            position: absolute !important;
            width: ${a}px !important;
            height: ${l}px !important;
            ${P}px !important;
            top: ${d}px !important;
          }
        `),()=>{L.contains(x)&&L.removeChild(x)}},[t]),E.jsx(N,{isPresent:t,childRef:s,sizeRef:f,children:r.cloneElement(e,{ref:u})})}const T=({children:e,initial:t,isPresent:n,onExitComplete:o,custom:i,presenceAffectsLayout:s,mode:f,anchorX:m,root:w})=>{const u=z(V),p=r.useId();let a=!0,l=r.useMemo(()=>(a=!1,{id:p,initial:t,isPresent:n,custom:i,onExitComplete:d=>{u.set(d,!0);for(const C of u.values())if(!C)return;o&&o()},register:d=>(u.set(d,!1),()=>u.delete(d))}),[n,u,o]);return s&&a&&(l={...l}),r.useMemo(()=>{u.forEach((d,C)=>u.set(C,!1))},[n]),r.useEffect(()=>{!n&&!u.size&&o&&o()},[n]),f==="popLayout"&&(e=E.jsx(O,{isPresent:n,anchorX:m,root:w,children:e})),E.jsx(D.Provider,{value:l,children:e})};function V(){return new Map}const $=e=>e.key||"";function I(e){const t=[];return r.Children.forEach(e,n=>{r.isValidElement(n)&&t.push(n)}),t}const ne=({children:e,custom:t,initial:n=!0,onExitComplete:o,presenceAffectsLayout:i=!0,mode:s="sync",propagate:f=!1,anchorX:m="left",root:w})=>{const[u,p]=H(f),a=r.useMemo(()=>I(e),[e]),l=f&&!u?[]:a.map($),d=r.useRef(!0),C=r.useRef(a),g=z(()=>new Map),P=r.useRef(new Set),[x,L]=r.useState(a),[y,v]=r.useState(a);Z(()=>{d.current=!1,C.current=a;for(let h=0;h<y.length;h++){const c=$(y[h]);l.includes(c)?(g.delete(c),P.current.delete(c)):g.get(c)!==!0&&g.set(c,!1)}},[y,l.length,l.join("-")]);const j=[];if(a!==x){let h=[...a];for(let c=0;c<y.length;c++){const R=y[c],M=$(R);l.includes(M)||(h.splice(c,0,R),j.push(R))}return s==="wait"&&j.length&&(h=j),v(I(h)),L(a),null}const{forceRender:k}=r.useContext(_);return E.jsx(E.Fragment,{children:y.map(h=>{const c=$(h),R=f&&!u?!1:a===y||l.includes(c),M=()=>{if(P.current.has(c))return;if(P.current.add(c),g.has(c))g.set(c,!0);else return;let A=!0;g.forEach(U=>{U||(A=!1)}),A&&(k==null||k(),v(C.current),f&&(p==null||p()),o&&o())};return E.jsx(T,{isPresent:R,initial:!d.current||n?void 0:!1,custom:t,presenceAffectsLayout:i,mode:s,root:w,onExitComplete:R?void 0:M,anchorX:m,children:h},c)})})};/**
 * @license lucide-react v0.562.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const X=e=>e.replace(/([a-z0-9])([A-Z])/g,"$1-$2").toLowerCase(),q=e=>e.replace(/^([A-Z])|[\s-_]+(\w)/g,(t,n,o)=>o?o.toUpperCase():n.toLowerCase()),W=e=>{const t=q(e);return t.charAt(0).toUpperCase()+t.slice(1)},S=(...e)=>e.filter((t,n,o)=>!!t&&t.trim()!==""&&o.indexOf(t)===n).join(" ").trim(),J=e=>{for(const t in e)if(t.startsWith("aria-")||t==="role"||t==="title")return!0};/**
 * @license lucide-react v0.562.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */var Q={xmlns:"http://www.w3.org/2000/svg",width:24,height:24,viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:2,strokeLinecap:"round",strokeLinejoin:"round"};/**
 * @license lucide-react v0.562.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Y=r.forwardRef(({color:e="currentColor",size:t=24,strokeWidth:n=2,absoluteStrokeWidth:o,className:i="",children:s,iconNode:f,...m},w)=>r.createElement("svg",{ref:w,...Q,width:t,height:t,stroke:e,strokeWidth:o?Number(n)*24/Number(t):n,className:S("lucide",i),...!s&&!J(m)&&{"aria-hidden":"true"},...m},[...f.map(([u,p])=>r.createElement(u,p)),...Array.isArray(s)?s:[s]]));/**
 * @license lucide-react v0.562.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const se=(e,t)=>{const n=r.forwardRef(({className:o,...i},s)=>r.createElement(Y,{ref:s,iconNode:t,className:S(`lucide-${X(W(e))}`,`lucide-${e}`,o),...i}));return n.displayName=W(e),n};export{ne as A,se as c};
