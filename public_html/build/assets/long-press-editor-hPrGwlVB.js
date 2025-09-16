class i{constructor(){this.longPressTimeout=null,this.longPressDuration=2e3,this.isLongPress=!1,this.startX=0,this.startY=0,this.maxMovement=10,this.currentElement=null,this.init()}init(){this.isPageOwner()&&(this.bindEvents(),this.addStyles())}isPageOwner(){var r,s;const t=window.currentUserId||((r=document.querySelector('meta[name="current-user-id"]'))==null?void 0:r.getAttribute("content")),e=window.pageUserId||((s=document.querySelector('meta[name="page-user-id"]'))==null?void 0:s.getAttribute("content"));return t&&e&&t===e}bindEvents(){[".article-preview",".service-card",".banners-banner",".gallery-item.editable-item"].forEach(e=>{document.querySelectorAll(e).forEach(s=>{this.addLongPressEvents(s)})})}addLongPressEvents(t){t.addEventListener("touchstart",e=>this.handleStart(e,t),{passive:!0}),t.addEventListener("touchend",e=>this.handleEnd(e,t),{passive:!0}),t.addEventListener("touchmove",e=>this.handleMove(e,t),{passive:!0}),t.addEventListener("touchcancel",e=>this.handleCancel(e,t),{passive:!0}),t.addEventListener("mousedown",e=>this.handleStart(e,t),{passive:!0}),t.addEventListener("mouseup",e=>this.handleEnd(e,t),{passive:!0}),t.addEventListener("mousemove",e=>this.handleMove(e,t),{passive:!0}),t.addEventListener("mouseleave",e=>this.handleCancel(e,t),{passive:!0}),t.addEventListener("contextmenu",e=>{this.isLongPress&&e.preventDefault()}),t.addEventListener("click",e=>{if(this.isLongPress)return e.preventDefault(),e.stopPropagation(),this.isLongPress=!1,!1})}handleStart(t,e){this.currentElement=e,this.isLongPress=!1;const r=t.touches?t.touches[0]:t;this.startX=r.clientX,this.startY=r.clientY,e.classList.add("long-press-active"),this.longPressTimeout=setTimeout(()=>{this.isLongPress=!0,this.triggerLongPress(e)},this.longPressDuration)}handleMove(t,e){if(!this.longPressTimeout)return;const r=t.touches?t.touches[0]:t,s=Math.abs(r.clientX-this.startX),n=Math.abs(r.clientY-this.startY);(s>this.maxMovement||n>this.maxMovement)&&this.handleCancel(t,e)}handleEnd(t,e){const r=this.isLongPress;if(this.cleanup(e),r)return!1}handleCancel(t,e){this.cleanup(e)}cleanup(t){this.longPressTimeout&&(clearTimeout(this.longPressTimeout),this.longPressTimeout=null),t&&t.classList.remove("long-press-active"),this.currentElement=null}triggerLongPress(t){t.classList.add("long-press-triggered"),navigator.vibrate&&navigator.vibrate(50);const e=this.getEditUrl(t);e&&setTimeout(()=>{window.location.href=e},100)}getEditUrl(t){var r;const e=(r=document.querySelector('meta[name="current-user-id"]'))==null?void 0:r.getAttribute("content");if(!e)return console.warn("Current user ID not found"),null;if(t.classList.contains("article-preview")){const s=t.getAttribute("data-article-id");if(s)return`/admin/user/${e}/articles/${s}/edit`}if(t.classList.contains("service-card")){const s=t.getAttribute("data-analytics-id");if(s)return`/admin/user/${e}/services/${s}/edit`}if(t.classList.contains("banners-banner")){const s=t.getAttribute("data-analytics-id");if(s)return`/admin/user/${e}/banners/${s}/edit`}return t.classList.contains("gallery-item")&&t.classList.contains("editable-item")?`/admin/user/${e}/gallery`:null}addStyles(){const t=`
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
        `,e=document.createElement("style");e.textContent=t,document.head.appendChild(e)}}document.addEventListener("DOMContentLoaded",()=>{setTimeout(()=>{new i},100)});window.LongPressEditor=i;
