class e{constructor(){this.deferredPrompt=null,this.installButton=null,this.init()}init(){window.addEventListener("beforeinstallprompt",t=>{console.log("PWA: beforeinstallprompt событие получено"),t.preventDefault(),this.deferredPrompt=t,this.showInstallButton()}),window.addEventListener("appinstalled",t=>{console.log("PWA: приложение установлено"),this.hideInstallButton(),this.deferredPrompt=null,this.showInstallSuccess()}),this.createInstallButton()}createInstallButton(){if(window.matchMedia("(display-mode: standalone)").matches||window.navigator.standalone===!0){console.log("PWA: приложение уже установлено");return}this.installButton=document.createElement("button"),this.installButton.innerHTML=`
            <i class="bi bi-download"></i>
            <span>Установить приложение</span>
        `,this.installButton.className="pwa-install-button",this.installButton.style.cssText=`
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #2A5885;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(42, 88, 133, 0.3);
            z-index: 1000;
            display: none;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        `,this.installButton.addEventListener("mouseenter",()=>{this.installButton.style.transform="translateY(-2px)",this.installButton.style.boxShadow="0 6px 20px rgba(42, 88, 133, 0.4)"}),this.installButton.addEventListener("mouseleave",()=>{this.installButton.style.transform="translateY(0)",this.installButton.style.boxShadow="0 4px 12px rgba(42, 88, 133, 0.3)"}),this.installButton.addEventListener("click",()=>{this.installPWA()}),document.body.appendChild(this.installButton)}showInstallButton(){this.installButton&&(this.installButton.style.display="flex",setTimeout(()=>{this.installButton.style.opacity="1",this.installButton.style.transform="translateY(0)"},100))}hideInstallButton(){this.installButton&&(this.installButton.style.display="none")}async installPWA(){if(!this.deferredPrompt){console.log("PWA: нет доступного промпта для установки");return}this.deferredPrompt.prompt();const{outcome:t}=await this.deferredPrompt.userChoice;console.log(`PWA: пользователь ${t==="accepted"?"принял":"отклонил"} установку`),this.deferredPrompt=null,t==="accepted"&&this.hideInstallButton()}showInstallSuccess(){const t=document.createElement("div");if(t.innerHTML=`
            <i class="bi bi-check-circle"></i>
            <span>Приложение успешно установлено!</span>
        `,t.style.cssText=`
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            z-index: 1001;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
            animation: slideInFromRight 0.3s ease;
        `,!document.querySelector("#pwa-animations")){const n=document.createElement("style");n.id="pwa-animations",n.textContent=`
                @keyframes slideInFromRight {
                    0% {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    100% {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
            `,document.head.appendChild(n)}document.body.appendChild(t),setTimeout(()=>{t.style.animation="slideInFromRight 0.3s ease reverse",setTimeout(()=>{document.body.removeChild(t)},300)},3e3)}static isPWASupported(){return"serviceWorker"in navigator&&"PushManager"in window}static isPWAInstalled(){return window.matchMedia("(display-mode: standalone)").matches||window.navigator.standalone===!0}}document.addEventListener("DOMContentLoaded",function(){e.isPWASupported()&&!e.isPWAInstalled()&&(window.pwaInstaller=new e)});window.PWAInstaller=e;
