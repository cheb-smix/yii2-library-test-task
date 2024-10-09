
class SubscriptionPopup {

    constructor(initialbtn) {
        this.csrfParam = document.querySelector(`head > [name=csrf-param]`).getAttribute("content");
        this.csrfToken = document.querySelector(`head > [name=csrf-token]`).getAttribute("content");
        this.authorId = initialbtn.dataset.authorId;
        this.authorName = initialbtn.dataset.authorName;
        this.errorTime = 3000;
        this.transitionTime = 300;
    }

    show = () => {
        this.background = document.createElement("div");
        this.background.className = "subscription-popup-background";
        this.background.addEventListener("click", this.hide);
    
        this.form = document.createElement("div");
        this.form.className = "subscription-popup-form";
        this.form.innerHTML = `<legend>Подписка на новые книги автора ${this.authorName}</legend>
        <div class="popup-phone-input-wrap">
            <input placeholder="Номер телефона" type="text" class="form-control" id="phone" value="+79003308311" />
        </div>
        <button class="btn">Подписаться</button>
        </form>`;
    
        document.body.appendChild(this.background);
        document.body.appendChild(this.form);

        this.phoneInput = document.querySelector("#phone");
        this.phoneInput.addEventListener("keyup", this.validate);
        this.phoneInput.addEventListener("focusout", () => {
            if (!this.validate(true)) {
                this.phoneInput.parentNode.classList.add("popup-input-warning");
                this.phoneInput.parentNode.dataset.warning = "* Некорректный номер телефона";
            } else {
                this.phoneInput.parentNode.classList.add("popup-input-correct");
            }
        });
        this.form.querySelector("button").addEventListener("click", e => {
            e.target.disabled = true;
            setTimeout(() => {
                e.target.disabled = false;
            }, this.errorTime);
            this.subscribe();
        });
    
        setTimeout(() => {
            this.background.classList.add("popup-shown");
            this.form.classList.add("popup-shown");
        }, this.transitionTime / 3);
    }

    hide = () => {
        this.background.classList.remove("popup-shown");
        this.form.classList.remove("popup-shown");
        setTimeout(() => {
            this.background.remove();
            this.form.remove();
        }, this.transitionTime)
    }

    validate = (final = false) => {
        if (this.phoneInput.parentNode.classList.contains("popup-input-warning")) {
            this.phoneInput.parentNode.classList.remove("popup-input-warning")
        }
        if (this.phoneInput.parentNode.classList.contains("popup-input-correct")) {
            this.phoneInput.parentNode.classList.remove("popup-input-correct")
        }
        let firstLetter = this.phoneInput.value.substring(0, 1);
        if (firstLetter != "+") {
            if (firstLetter == "8") {
                this.phoneInput.value = "+7" + this.phoneInput.value.substring(1);
            } else if (firstLetter == "7") {
                this.phoneInput.value = "+" + this.phoneInput.value;
            }
        }
        if (final) {
            let regex = /^\+[0-9]{11}$/;
            let match = this.phoneInput.value.match(regex);
            if (!match || !match[0]) {
                return false;
            }
        }
        return true;
    }

    subscribe = () => {
        if (!this.phoneInput.value) {
            this.phoneInput.parentNode.classList.add("popup-input-warning");
            this.phoneInput.parentNode.dataset.warning = "* Введите номер телефона для подписки";
            return;
        }
        if (!this.validate(true)) {
            this.phoneInput.parentNode.classList.add("popup-input-warning");
            this.phoneInput.parentNode.dataset.warning = "* Некорректный номер телефона";
            return;
        } else {
            this.phoneInput.parentNode.classList.add("popup-input-correct");
        }
    
        let postData = {
            phone: this.phoneInput.value
        };
        postData[this.csrfParam] = this.csrfToken;
        postData = JSON.stringify(postData);
    
        let xhttp = new XMLHttpRequest();
        xhttp.responseType = 'json';
        xhttp.parent = this;
        xhttp.onload = function() {
            if (this.status == 200 && this.response.success) {

                let i = document.createElement("i");
                i.className = "fa fa-5x fa-check-circle text-success";

                let alert = document.createElement("div");
                alert.className = "alert alert-success";
                alert.innerHTML = this.response.message;

                this.parent.form.innerHTML = "";
                this.parent.form.appendChild(i);
                this.parent.form.appendChild(alert);

                setTimeout(() => {
                    i.classList.add("inited");
                }, 100);

                setTimeout(() => {
                    this.parent.hide();
                }, this.parent.errorTime);

            } else {
                let alert = document.createElement("div");
                alert.className = "alert alert-danger";
                alert.innerHTML = this.response.message;
                document.querySelector(".subscription-popup-form > legend").after(alert);
                setTimeout(() => {
                    alert.remove();
                }, this.errorTime);
            }
        }
        xhttp.open("POST", `/author/subscribe?id=${this.authorId}`);
        xhttp.setRequestHeader("Content-type", "application/json; charset=UTF-8");
        xhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhttp.send(postData);
    }

}
