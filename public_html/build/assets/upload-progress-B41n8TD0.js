function a(){const s=document.getElementById("upload-progress-bar");s&&s.remove();const e=document.createElement("div");return e.id="upload-progress-bar",e.style.cssText=`
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 9999;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 20px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    `,e.innerHTML=`
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="text-center mb-3">
                        <h6 class="mb-2">Загрузка изображения...</h6>
                        <small class="text-muted">Пожалуйста, подождите</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                             role="progressbar" 
                             style="width: 0%;" 
                             id="upload-progress-bar-fill"></div>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted" id="upload-progress-text">0%</small>
                    </div>
                </div>
            </div>
        </div>
    `,document.body.appendChild(e),e}function r(s){const e=document.getElementById("upload-progress-bar-fill"),t=document.getElementById("upload-progress-text");e&&t&&(e.style.width=s+"%",t.textContent=Math.round(s)+"%")}function o(){const s=document.getElementById("upload-progress-bar");s&&(s.style.opacity="0",s.style.transition="opacity 0.3s ease",setTimeout(()=>{s.remove()},300))}function d(s=2e3){a();let e=0;const t=100/(s/50),i=setInterval(()=>{if(e+=t,e>=90){clearInterval(i),r(90);return}r(e)},50);return i}function n(){r(100),setTimeout(()=>{o()},500)}function l(s="Ошибка загрузки изображения"){o();const e=document.createElement("div");e.style.cssText=`
        position: fixed;
        top: 20px;
        right: 20px;
        background: #dc3545;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        z-index: 10000;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        font-size: 14px;
        max-width: 300px;
    `,e.innerHTML=`
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <span>${s}</span>
        </div>
    `,document.body.appendChild(e),setTimeout(()=>{e.style.opacity="0",e.style.transition="opacity 0.3s ease",setTimeout(()=>{e.remove()},300)},5e3)}window.createProgressBar=a;window.updateProgressBar=r;window.hideProgressBar=o;window.simulateUploadProgress=d;window.completeUploadProgress=n;window.showUploadError=l;
