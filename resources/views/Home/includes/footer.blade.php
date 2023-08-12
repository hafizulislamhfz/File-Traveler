{{-- footer section start --}}
    <section class="footer">
        <footer>
            <span class="dev-info"><a href="https://hafizulislamhfz.github.io/Hafizul-portfolio/" target="_blank">Hafizul Islam</a> |
                <span class="far fa-copyright"></span>
                    <span id="copyright">
                        <script>document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))</script>
                    </span>
                All rights reserved.
            </span>
            <span class="total-share">Total File Shared: {{ str_pad($totalRows, 7, '0', STR_PAD_LEFT) }} Files</span>
        </footer>
    </section>
    {{-- footer section end --}}
