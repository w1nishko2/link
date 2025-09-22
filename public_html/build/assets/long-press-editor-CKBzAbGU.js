class i{constructor(){this.longPressTimeout=null,this.longPressDuration=2e3,this.isLongPress=!1,this.startX=0,this.startY=0,this.maxMovement=10,this.currentElement=null,this.init()}init(){this.isPageOwner()&&(this.bindEvents(),this.addStyles(),this.ensureScrollEnabled())}isPageOwner(){var r,s;const e=window.currentUserId||((r=document.querySelector('meta[name="current-user-id"]'))==null?void 0:r.getAttribute("content")),t=window.pageUserId||((s=document.querySelector('meta[name="page-user-id"]'))==null?void 0:s.getAttribute("content"));return e&&t&&e===t}bindEvents(){[".article-preview",".service-card",".banners-banner",".gallery-item.editable-item"].forEach(t=>{document.querySelectorAll(t).forEach(s=>{this.addLongPressEvents(s)})})}addLongPressEvents(e){e.addEventListener("touchstart",t=>this.handleStart(t,e),{passive:!0}),e.addEventListener("touchend",t=>this.handleEnd(t,e),{passive:!0}),e.addEventListener("touchmove",t=>this.handleMove(t,e),{passive:!0}),e.addEventListener("touchcancel",t=>this.handleCancel(t,e),{passive:!0}),e.addEventListener("mousedown",t=>this.handleStart(t,e),{passive:!0}),e.addEventListener("mouseup",t=>this.handleEnd(t,e),{passive:!0}),e.addEventListener("mousemove",t=>this.handleMove(t,e),{passive:!0}),e.addEventListener("mouseleave",t=>this.handleCancel(t,e),{passive:!0}),e.addEventListener("contextmenu",t=>{this.isLongPress&&t.preventDefault()}),e.addEventListener("click",t=>{if(this.isLongPress)return t.preventDefault(),t.stopPropagation(),this.isLongPress=!1,!1})}handleStart(e,t){this.currentElement=t,this.isLongPress=!1;const r=e.touches?e.touches[0]:e;this.startX=r.clientX,this.startY=r.clientY,t.classList.add("long-press-active"),this.longPressTimeout=setTimeout(()=>{this.isLongPress=!0,this.triggerLongPress(t)},this.longPressDuration)}handleMove(e,t){if(!this.longPressTimeout)return;const r=e.touches?e.touches[0]:e,s=Math.abs(r.clientX-this.startX),n=Math.abs(r.clientY-this.startY);(s>this.maxMovement||n>this.maxMovement)&&this.handleCancel(e,t)}handleEnd(e,t){const r=this.isLongPress;if(this.cleanup(t),r)return!1}handleCancel(e,t){this.cleanup(t)}cleanup(e){this.longPressTimeout&&(clearTimeout(this.longPressTimeout),this.longPressTimeout=null),e&&e.classList.remove("long-press-active"),this.currentElement=null}triggerLongPress(e){e.classList.add("long-press-triggered"),navigator.vibrate&&navigator.vibrate(50);const t=this.getEditUrl(e);t&&setTimeout(()=>{window.location.href=t},100)}getEditUrl(e){var r;const t=(r=document.querySelector('meta[name="current-user-id"]'))==null?void 0:r.getAttribute("content");if(!t)return console.warn("Current user ID not found"),null;if(e.classList.contains("article-preview")){const s=e.getAttribute("data-article-id");if(s)return`/admin/user/${t}/articles/${s}/edit`}if(e.classList.contains("service-card")){const s=e.getAttribute("data-analytics-id");if(s)return`/admin/user/${t}/services/${s}/edit`}if(e.classList.contains("banners-banner")){const s=e.getAttribute("data-analytics-id");if(s)return`/admin/user/${t}/banners/${s}/edit`}return e.classList.contains("gallery-item")&&e.classList.contains("editable-item")?`/admin/user/${t}/gallery`:null}addStyles(){const e=`
            .long-press-active {
                transition: transform 0.1s ease-in-out, opacity 0.1s ease-in-out;
                transform: scale(0.98);
                opacity: 0.8;
            }
            
            .long-press-triggered {
                transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
                transform: scale(1.02);
                box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
                border: 2px solid rgba(0, 123, 255, 0.5);
                border-radius: 8px;
                position: relative;
                z-index: 10;
            }
            
            .long-press-triggered::before {
                content: "✏️ Редактировать";
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgba(0, 123, 255, 0.95);
                color: white;
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 14px;
                font-weight: 500;
                white-space: nowrap;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                z-index: 11;
                pointer-events: none;
                backdrop-filter: blur(4px);
            }

            /* Специальные стили для разных типов элементов */
            .article-preview.long-press-triggered {
                border-radius: 12px;
            }

            .service-card.long-press-triggered {
                border-radius: 16px;
            }

            .banners-banner.long-press-triggered {
                border-radius: 12px;
            }

            .gallery-item.long-press-triggered {
                border-radius: 8px;
            }

            /* Добавляем прогресс-бар для показа процесса долгого нажатия */
            .long-press-active::after {
                content: "";
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                background: linear-gradient(90deg, #007bff, #0056b3);
                border-radius: 0 0 8px 8px;
                animation: longPressProgress 2s linear;
                z-index: 5;
            }

            @keyframes longPressProgress {
                from {
                    width: 0%;
                }
                to {
                    width: 100%;
                }
            }
        `,t=document.createElement("style");t.textContent=e,document.head.appendChild(t)}ensureScrollEnabled(){document.body.style.overflow="",document.body.style.position="",document.body.style.width="",document.body.style.height="",document.body.style.touchAction="",document.body.classList.remove("mobile-sidebar-open"),setInterval(()=>{this.isPageOwner()&&window.innerWidth>=768&&(document.body.style.overflow="",document.body.classList.remove("mobile-sidebar-open"))},2e3)}}document.addEventListener("DOMContentLoaded",()=>{setTimeout(()=>{new i},100)});window.LongPressEditor=i;
