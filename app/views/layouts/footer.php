</div> <!-- cierre de main-content -->

<footer>
    <style>
        footer {
            background: #1a2e44;
            color: rgba(255,255,255,0.5);
            padding: 1.25rem 2rem;
            margin-top: 3rem;
            font-size: 0.82rem;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .footer-brand {
            font-weight: 600;
            color: rgba(255,255,255,0.8);
        }

        .footer-brand span { color: #4da6ff; }

        .footer-links {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            list-style: none;
        }

        .footer-links a {
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .footer-links a:hover { color: rgba(255,255,255,0.8); }

        .footer-copy {
            color: rgba(255,255,255,0.3);
        }
    </style>

    <div class="footer-inner">
        <span class="footer-brand">Inmo<span>Casa</span></span>

        <ul class="footer-links">
            <li>
                <a href="../../public/index.php?action=dashboard">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="../../public/index.php?action=propiedades">
                    <i class="fa-solid fa-house"></i> Propiedades
                </a>
            </li>
            <li>
                <a href="../../public/index.php?action=contratos">
                    <i class="fa-solid fa-file-contract"></i> Contratos
                </a>
            </li>
            <li>
                <a href="../../public/index.php?action=pagos">
                    <i class="fa-solid fa-credit-card"></i> Pagos
                </a>
            </li>
        </ul>

        <span class="footer-copy">
            &copy; <?= date('Y') ?> InmoCasa — Proyecto DAW
        </span>
    </div>
</footer>

</body>
</html>