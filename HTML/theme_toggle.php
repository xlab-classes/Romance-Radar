<style>
      .dark-mode {
            background-color: palevioletred;
        }
</style>
<script>
        if(window.sessionStorage.getItem('theme') && window.sessionStorage.getItem('theme') == 'dark'){
            var element = document.body;
            element.classList.toggle("dark-mode");
        }
        function myFunction() {
            var element = document.body;
            element.classList.toggle("dark-mode");
            if(window.sessionStorage.getItem('theme') && window.sessionStorage.getItem('theme') == 'dark'){
                window.sessionStorage.setItem('theme', 'light');
            }else{
                window.sessionStorage.setItem('theme', 'dark');
            }
        }
    </script>

<button onclick="myFunction()">Toggle dark mode</button>