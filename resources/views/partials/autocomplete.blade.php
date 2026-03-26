<style>
    .suggestions-box { position: absolute; background: white; border: 1px solid #ccc; z-index: 9999; width: 100%; }
    .suggestion-item { padding: 6px 8px; cursor: pointer; }
    .suggestion-item:hover { background: #f0f0f0; }
</style>

<script>
    function setupAutocomplete(inputEl, getCategoryId) {
        let box = document.createElement("div");
        box.className = "suggestions-box";
        inputEl.parentNode.appendChild(box);

        inputEl.addEventListener("keyup", function () {
            let term = this.value.trim();
            if (term.length < 3) { box.innerHTML = ""; return; }

            let url = `/menu-item-search?term=` + encodeURIComponent(term);
            if (getCategoryId) {
                let catId = getCategoryId(inputEl);
                if (catId) url += `&category_id=` + encodeURIComponent(catId);
            }

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    box.innerHTML = "";
                    const cap = v => v ? v.charAt(0).toUpperCase() + v.slice(1) : v;
                    data.forEach(item => {
                        let div = document.createElement("div");
                        div.className = "suggestion-item";
                        div.textContent = cap(item.name);
                        div.addEventListener("mousedown", (e) => { e.preventDefault(); inputEl.value = cap(item.name); box.innerHTML = ""; });
                        box.appendChild(div);
                    });
                });
        });

        inputEl.addEventListener("blur", () => setTimeout(() => box.innerHTML = "", 100));
    }
</script>
