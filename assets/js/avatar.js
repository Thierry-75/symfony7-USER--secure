let links = document.querySelectorAll("[data-delete]");

for(let link of links)
    {
        link.addEventListener('click',function(e){
            e.preventDefault();
            if(confirm('Delete this picture ?')){
                //requete ajax
                fetch(this.getAttribute("href"),{
                    method: "DELETE",
                    headers: {
                        "X-Request-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ "_token": this.dataset.toke })
                }),then( data => {
                    if(data.success){
                        this.parentElement.remove();
                    }else{
                        alert(data.error);
                    }
                })
            }
        });
    }