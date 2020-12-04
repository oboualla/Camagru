if (document.getElementById('searchsubmit')) {
    document.getElementById('searchsubmit').onclick = () => {
        search = document.getElementById('searchinput').value;

        if (search != '' && search.trim() != '') {
            window.location.href = '/search?' + 'search=' + search;
        }
    }
}