document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.fg-antrag-header').forEach(function(h){
        h.addEventListener('click', function(){
            this.closest('.fg-antrag-item').classList.toggle('open');
        });
    });
    document.querySelectorAll('.fg-filter-btn').forEach(function(btn){
        btn.addEventListener('click', function(){
            document.querySelectorAll('.fg-filter-btn').forEach(function(b){ b.classList.remove('active'); });
            this.classList.add('active');
            var f = this.getAttribute('data-filter');
            document.querySelectorAll('.fg-antrag-item').forEach(function(item){
                item.style.display = (f === 'alle' || item.getAttribute('data-status') === f) ? '' : 'none';
            });
        });
    });
});
