import{r as t,A as r,j as e}from"./app-CQ2xFxzx.js";import c from"./NavBar-C09VzQMO.js";import l from"./Footer-D0FR_cvt.js";function x({children:i,auth:a}){const[n,d]=t.useState(!1),[m,f]=t.useState(null),o=t.useRef(null);return t.useEffect(()=>{r.init({duration:500,easing:"ease-out-cubic",once:!0,offset:80})},[]),t.useEffect(()=>{const s=u=>{o.current&&!o.current.contains(u.target)&&d(!1)};return n&&document.addEventListener("mousedown",s),()=>document.removeEventListener("mousedown",s)},[n]),e.jsxs("div",{className:"min-h-screen bg-gray-50",children:[e.jsx("nav",{className:"bg-white shadow-lg fixed w-full z-50","data-aos":"fade-down","data-aos-duration":"1400",children:e.jsx(c,{auth:a})}),e.jsx("main",{className:"pt-16",children:i}),e.jsx("footer",{className:"bg-blue-950 text-white py-12","data-aos":"fade-up","data-aos-duration":"1400",children:e.jsx(l,{})}),e.jsx("script",{children:`
                document.addEventListener('DOMContentLoaded', function() {
                    const mobileMenuButton = document.querySelector('button.md\\:hidden');
                    const mobileMenu = document.querySelector('.md\\:hidden.bg-white');
                    
                    if (mobileMenuButton && mobileMenu) {
                        mobileMenuButton.addEventListener('click', function() {
                            mobileMenu.classList.toggle('hidden');
                        });
                    }
                });
            `}),e.jsx("script",{children:`
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof AOS !== 'undefined') {
                        AOS.refresh();
                    }
                });
            `})]})}export{x as G};
