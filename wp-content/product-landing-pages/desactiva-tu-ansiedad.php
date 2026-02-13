<?php
/**
 * Desactiva Tu Ansiedad — Self-contained Landing Page
 *
 * This file outputs a COMPLETE HTML page (doctype, head, body).
 * It is loaded by the mu-plugin product-landing-pages.php via template_redirect.
 * It bypasses the WordPress theme entirely.
 *
 * WooCommerce is fully loaded, so $product, wc_get_checkout_url(), etc. work.
 */

defined('ABSPATH') || exit;

// Get WooCommerce product and checkout URL
global $product;
$product_id = $product ? $product->get_id() : 0;
$checkout_url = $product_id
    ? esc_url(add_query_arg('add-to-cart', $product_id, wc_get_checkout_url()))
    : '#';

// Product image
$cover_image = '';
if ($product && $product->get_image_id()) {
    $cover_image = wp_get_attachment_image_url($product->get_image_id(), 'full');
}
if (!$cover_image) {
    $cover_image = content_url('/uploads/woocommerce-placeholder-300x300.png');
}

// CSS files from theme
$theme_uri = get_template_directory_uri();
$theme_dir = get_template_directory();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title>Desactiva Tu Ansiedad — Método R.E.N.A.C.E. | Guía Práctica de 30 Días</title>
    <meta name="description"
        content="Descubre el método de 6 fases creado por una psicóloga clínica para superar la ansiedad. Incluye plan de 30 días, Kit de Emergencia y 3 bonos exclusivos. Garantía de 7 días.">
    <meta name="keywords"
        content="ansiedad, superar ansiedad, método RENACE, crisis de ansiedad, ataques de pánico, mindfulness, relajación, libro ansiedad">
    <meta name="author" content="Sofía Morales">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="product">
    <meta property="og:title" content="Desactiva Tu Ansiedad — Método R.E.N.A.C.E.">
    <meta property="og:description"
        content="El sistema de 6 fases para superar la ansiedad. Guía práctica con plan de 30 días + 3 bonos exclusivos.">
    <meta property="og:image" content="<?php echo esc_url($cover_image); ?>">
    <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
    <meta property="og:locale" content="es_ES">
    <meta property="product:price:amount" content="27.00">
    <meta property="product:price:currency" content="USD">

    <!-- Preload -->
    <?php if ($cover_image): ?>
        <link rel="preload" href="<?php echo esc_url($cover_image); ?>" as="image">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400&family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- CSS -->
    <?php
    $css_files = ['variables', 'base', 'layout', 'components', 'animations'];
    foreach ($css_files as $file) {
        $path = $theme_dir . '/assets/css/' . $file . '.css';
        if (file_exists($path)) {
            echo '<link rel="stylesheet" href="' . esc_url($theme_uri . '/assets/css/' . $file . '.css?v=' . filemtime($path)) . '">' . "\n    ";
        }
    }
    ?>

    <!-- Schema.org -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "Desactiva Tu Ansiedad - Método R.E.N.A.C.E.",
        "description": "Guía completa de 16 capítulos con el Método R.E.N.A.C.E. para superar la ansiedad. Incluye plan de 30 días y 3 bonos exclusivos.",
        "image": "<?php echo esc_url($cover_image); ?>",
        "author": {
            "@type": "Person",
            "name": "Sofía Morales",
            "jobTitle": "Psicóloga Clínica"
        },
        "offers": {
            "@type": "Offer",
            "price": "27.00",
            "priceCurrency": "USD",
            "availability": "https://schema.org/InStock"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.8",
            "reviewCount": "347"
        }
    }
    </script>

    <?php wp_head(); ?>
</head>

<body <?php body_class('landing-page landing-page--desactiva'); ?>>
    <?php wp_body_open(); ?>


    <!-- ============================================
         SECCIÓN 1 — HERO
         ============================================ -->
    <section class="hero" id="hero">
        <div class="container hero__container">
            <div class="hero__content">
                <span class="badge hero__badge reveal">
                    <span>🌿</span> Método validado con +500 pacientes
                </span>

                <h1 class="hero__title reveal reveal--delay-1">
                    La ansiedad no es tu culpa.<br>
                    Pero ahora tienes el <em>sistema</em> para desactivarla.
                </h1>

                <p class="hero__subtitle reveal reveal--delay-2">
                    Descubre el Método R.E.N.A.C.E. — 6 fases diseñadas por una psicóloga clínica
                    que vivió la ansiedad en carne propia. Con un plan accionable de 30 días.
                </p>

                <div class="hero__cta-group reveal reveal--delay-3">
                    <a href="#oferta" class="btn btn--primary btn--large" id="cta-hero">
                        <span class="btn__icon">📖</span> Quiero Mi Guía
                    </a>
                    <a href="#metodo" class="btn btn--secondary">
                        Conocer el Método
                    </a>
                </div>

                <p class="hero__micro reveal reveal--delay-4">
                    Acceso inmediato · Descarga digital · Garantía de 7 días
                </p>
            </div>

            <div class="hero__visual reveal reveal--right reveal--delay-2">
                <img src="<?php echo esc_url($cover_image); ?>" alt="Ebook Desactiva Tu Ansiedad — Método R.E.N.A.C.E."
                    class="hero__mockup" width="420" height="560" fetchpriority="high">
            </div>
        </div>
    </section>


    <!-- ============================================
         SECCIÓN 2 — ESPEJO (Identificación)
         ============================================ -->
    <section class="section section--white" id="espejo">
        <div class="container container--narrow text-center">
            <span class="section__eyebrow reveal">¿Te suena familiar?</span>
            <h2 class="section__title reveal">Quizás reconoces alguna de estas señales</h2>
            <p class="section__subtitle reveal">No estás exagerando. No estás loco/a. Y definitivamente no estás solo/a.
            </p>

            <div class="mirror-list">
                <div class="mirror-list__item reveal reveal--delay-1">
                    <span class="mirror-list__icon">🔴</span>
                    <span>Te despiertas a las 3 AM con el corazón acelerado, convencido/a de que algo terrible va a
                        pasar.</span>
                </div>
                <div class="mirror-list__item reveal reveal--delay-2">
                    <span class="mirror-list__icon">🔴</span>
                    <span>Cancelas planes con amigos porque simplemente «no puedes» salir de casa.</span>
                </div>
                <div class="mirror-list__item reveal reveal--delay-3">
                    <span class="mirror-list__icon">🔴</span>
                    <span>Sientes un nudo en el pecho que no se va — los médicos dicen que «todo está bien», pero tú
                        sabes que algo no está bien.</span>
                </div>
                <div class="mirror-list__item reveal reveal--delay-4">
                    <span class="mirror-list__icon">🔴</span>
                    <span>Has probado respirar profundo, meditar, «pensar positivo»... y nada parece funcionar más de 5
                        minutos.</span>
                </div>
                <div class="mirror-list__item reveal reveal--delay-5">
                    <span class="mirror-list__icon">🔴</span>
                    <span>Todos te dicen «relájate» como si fuera tan fácil como apretar un botón.</span>
                </div>
            </div>

            <p class="reveal"
                style="margin-top: var(--space-8); font-size: var(--text-lg); color: var(--teal-deep); font-weight: 500;">
                Si marcaste al menos dos… <strong>este libro fue escrito pensando en ti.</strong>
            </p>
        </div>
    </section>


    <!-- ============================================
         SECCIÓN 3 — AGITACIÓN EMPÁTICA
         ============================================ -->
    <section class="section section--cream" id="agitacion">
        <div class="container container--narrow text-center">
            <h2 class="section__title reveal">Lo que nadie te dice sobre la ansiedad no tratada</h2>

            <div class="card card--accent reveal" style="text-align: left; margin-top: var(--space-6);">
                <p><strong>La ansiedad no se «quita sola».</strong> Sin las herramientas adecuadas, tiende a escalar.
                </p>
                <p>Lo que empieza como preocupación constante se convierte en ataques de pánico. Lo que empieza como
                    insomnio leve se convierte en noches enteras sin dormir. Lo que empieza como evitar una fiesta se
                    convierte en no poder salir de tu habitación.</p>
                <p>Según la OMS, <strong>el 40% de personas con ansiedad no tratada desarrollan depresión</strong> en
                    los siguientes 12 meses. No porque sean débiles — sino porque no tenían un sistema que les mostrara
                    el camino de salida.</p>
                <p style="margin-bottom: 0; color: var(--sage-dark); font-weight: 500;">La buena noticia: <strong>la
                        ansiedad se puede desactivar</strong>. No con fuerza de voluntad. Con un sistema.</p>
            </div>
        </div>
    </section>


    <!-- ============================================
         SECCIÓN 4 — MÉTODO R.E.N.A.C.E.
         ============================================ -->
    <section class="section section--white" id="metodo">
        <div class="container text-center">
            <span class="section__eyebrow reveal">La solución</span>
            <h2 class="section__title reveal">El Método R.E.N.A.C.E.</h2>
            <p class="section__subtitle reveal">6 fases probadas que te llevan desde entender tu ansiedad hasta
                construir una vida libre de ella.</p>

            <div class="renace-grid" style="max-width: 900px; margin: 0 auto;">
                <div class="renace-step reveal reveal--delay-1">
                    <div class="renace-step__letter">R</div>
                    <div>
                        <div class="renace-step__word">Reconoce</div>
                        <p class="renace-step__desc">Entiende qué es realmente la ansiedad, cómo funciona tu cerebro y
                            por qué la tienes tú.</p>
                    </div>
                </div>
                <div class="renace-step reveal reveal--delay-2">
                    <div class="renace-step__letter">E</div>
                    <div>
                        <div class="renace-step__word">Explora</div>
                        <p class="renace-step__desc">Mapea tus patrones personales, tus disparadores y tus señales de
                            alerta temprana.</p>
                    </div>
                </div>
                <div class="renace-step reveal reveal--delay-3">
                    <div class="renace-step__letter">N</div>
                    <div>
                        <div class="renace-step__word">Neutraliza</div>
                        <p class="renace-step__desc">Domina las herramientas: respiración, reestructuración mental,
                            mindfulness y técnicas corporales.</p>
                    </div>
                </div>
                <div class="renace-step reveal reveal--delay-4">
                    <div class="renace-step__letter">A</div>
                    <div>
                        <div class="renace-step__word">Actúa</div>
                        <p class="renace-step__desc">Pon todo en práctica con un plan paso a paso de 30 días — día por
                            día, sin adivinanzas.</p>
                    </div>
                </div>
                <div class="renace-step reveal reveal--delay-5">
                    <div class="renace-step__letter">C</div>
                    <div>
                        <div class="renace-step__word">Construye</div>
                        <p class="renace-step__desc">Diseña un estilo de vida que prevenga la ansiedad: sueño,
                            alimentación, relaciones, hábitos.</p>
                    </div>
                </div>
                <div class="renace-step reveal reveal--delay-6">
                    <div class="renace-step__letter">E</div>
                    <div>
                        <div class="renace-step__word">Evoluciona</div>
                        <p class="renace-step__desc">Mantén el progreso, prevé recaídas y conviértete en un apoyo para
                            quienes te rodean.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================
         SECCIÓN 5 — CONTENIDO (16 Capítulos)
         ============================================ -->
    <section class="section section--cream" id="contenido">
        <div class="container text-center">
            <span class="section__eyebrow reveal">Lo que encontrarás</span>
            <h2 class="section__title reveal">16 Capítulos + Plan de 30 Días</h2>
            <p class="section__subtitle reveal">Cada capítulo construye sobre el anterior. Cada ejercicio tiene un
                propósito. Nada es relleno.</p>

            <div class="chapters-grid" style="max-width: 900px; margin: 0 auto; text-align: left;">
                <!-- FASE R -->
                <div class="phase-divider reveal" style="grid-column: 1 / -1;">
                    <span class="phase-divider__line"></span>
                    <span class="phase-divider__label">🟢 Fase R — Reconoce</span>
                    <span class="phase-divider__line"></span>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">1</div>
                    <div>
                        <div class="chapter-card__title">El Monstruo Invisible</div>
                        <p class="chapter-card__desc">Qué es realmente la ansiedad y por qué no es tu enemigo.</p>
                    </div>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">2</div>
                    <div>
                        <div class="chapter-card__title">El Mapa del Caos</div>
                        <p class="chapter-card__desc">Cómo la ansiedad secuestra tu cuerpo — síntoma por síntoma.</p>
                    </div>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">3</div>
                    <div>
                        <div class="chapter-card__title">Las Raíces Ocultas</div>
                        <p class="chapter-card__desc">Por qué TÚ tienes ansiedad — genética, crianza y experiencias.</p>
                    </div>
                </div>

                <!-- FASE E -->
                <div class="phase-divider reveal" style="grid-column: 1 / -1;">
                    <span class="phase-divider__line"></span>
                    <span class="phase-divider__label">🔍 Fase E — Explora</span>
                    <span class="phase-divider__line"></span>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">4</div>
                    <div>
                        <div class="chapter-card__title">El Detective Interior</div>
                        <p class="chapter-card__desc">Mapea tus patrones y disparadores personales.</p>
                    </div>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">5</div>
                    <div>
                        <div class="chapter-card__title">Anatomía de una Espiral</div>
                        <p class="chapter-card__desc">Entiende tus crisis antes de que ocurran.</p>
                    </div>
                </div>

                <!-- FASE N -->
                <div class="phase-divider reveal" style="grid-column: 1 / -1;">
                    <span class="phase-divider__line"></span>
                    <span class="phase-divider__label">⚡ Fase N — Neutraliza</span>
                    <span class="phase-divider__line"></span>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">6</div>
                    <div>
                        <div class="chapter-card__title">Respira para Vivir</div>
                        <p class="chapter-card__desc">Tu arsenal de técnicas de respiración para cada situación.</p>
                    </div>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">7</div>
                    <div>
                        <div class="chapter-card__title">Hackea tu Mente</div>
                        <p class="chapter-card__desc">Reestructuración cognitiva práctica — cambia cómo piensas.</p>
                    </div>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">8</div>
                    <div>
                        <div class="chapter-card__title">El Ancla al Presente</div>
                        <p class="chapter-card__desc">Mindfulness sin misticismo — presencia pura y funcional.</p>
                    </div>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">9</div>
                    <div>
                        <div class="chapter-card__title">Técnicas Corporales</div>
                        <p class="chapter-card__desc">Desbloquea el cuerpo para liberar la mente.</p>
                    </div>
                </div>

                <!-- FASE A -->
                <div class="phase-divider reveal" style="grid-column: 1 / -1;">
                    <span class="phase-divider__line"></span>
                    <span class="phase-divider__label">🚀 Fase A — Actúa</span>
                    <span class="phase-divider__line"></span>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">10</div>
                    <div>
                        <div class="chapter-card__title">Semana 1: Cimientos</div>
                        <p class="chapter-card__desc">Días 1-7 — Establece la base de tu nueva rutina.</p>
                    </div>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">11</div>
                    <div>
                        <div class="chapter-card__title">Semana 2: Profundización</div>
                        <p class="chapter-card__desc">Días 8-14 — Integra las técnicas en tu día a día.</p>
                    </div>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">12</div>
                    <div>
                        <div class="chapter-card__title">Semanas 3-4: Dominio</div>
                        <p class="chapter-card__desc">Días 15-30 — Consolida y automatiza tu progreso.</p>
                    </div>
                </div>

                <!-- FASE C -->
                <div class="phase-divider reveal" style="grid-column: 1 / -1;">
                    <span class="phase-divider__line"></span>
                    <span class="phase-divider__label">🏡 Fase C — Construye</span>
                    <span class="phase-divider__line"></span>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">13</div>
                    <div>
                        <div class="chapter-card__title">Tu Estilo de Vida Anti-Ansiedad</div>
                        <p class="chapter-card__desc">Sueño, alimentación y hábitos que previenen recaídas.</p>
                    </div>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">14</div>
                    <div>
                        <div class="chapter-card__title">Relaciones y Ansiedad Social</div>
                        <p class="chapter-card__desc">Cómo manejar la ansiedad en tu vida social y laboral.</p>
                    </div>
                </div>

                <!-- FASE E -->
                <div class="phase-divider reveal" style="grid-column: 1 / -1;">
                    <span class="phase-divider__line"></span>
                    <span class="phase-divider__label">🦋 Fase E — Evoluciona</span>
                    <span class="phase-divider__line"></span>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">15</div>
                    <div>
                        <div class="chapter-card__title">Cuándo Necesitas Más</div>
                        <p class="chapter-card__desc">Guía honesta sobre terapia y ayuda profesional.</p>
                    </div>
                </div>
                <div class="chapter-card reveal">
                    <div class="chapter-card__num">16</div>
                    <div>
                        <div class="chapter-card__title">Tu Nueva Vida Comienza Ahora</div>
                        <p class="chapter-card__desc">Mantén el progreso y ayuda a quienes amas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================
         SECCIÓN 6 — AUTORIDAD (Sofía Morales)
         ============================================ -->
    <section class="section section--white" id="autora">
        <div class="container">
            <div class="author-section" style="max-width: 800px; margin: 0 auto;">
                <img src="<?php echo esc_url($cover_image); ?>" alt="Sofía Morales — Psicóloga Clínica"
                    class="author-section__img reveal reveal--left" width="160" height="160" loading="lazy"
                    decoding="async">

                <div class="reveal reveal--right">
                    <span class="section__eyebrow">Sobre la autora</span>
                    <h2 class="section__title">Sofía Morales</h2>
                    <p>Tenía 27 años cuando tuve mi primer ataque de pánico en un supermercado. Mi corazón se disparó,
                        las luces se volvieron demasiado brillantes, el suelo pareció moverse. Dejé el carrito y salí
                        corriendo al estacionamiento.</p>
                    <p>Ese fue el día que mi vida se dividió en un «antes» y un «después». Irónico — una psicóloga que
                        no reconocía su propia ansiedad.</p>
                    <p>Pero fue esa experiencia la que transformó mi enfoque profesional por completo. <strong>Creé el
                            Método R.E.N.A.C.E. porque era el sistema que yo misma necesité</strong> — y que ahora ha
                        ayudado a más de 500 pacientes.</p>

                    <div class="author-section__credentials">
                        <span class="badge">🎓 Psicóloga Clínica</span>
                        <span class="badge">📅 +10 años de experiencia</span>
                        <span class="badge">👥 +500 pacientes</span>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================
         SECCIÓN 7 — TESTIMONIOS
         ============================================ -->
    <section class="section section--cream" id="testimonios">
        <div class="container text-center">
            <span class="section__eyebrow reveal">Historias reales</span>
            <h2 class="section__title reveal">Lo que dicen quienes ya lo leyeron</h2>
            <p class="section__subtitle reveal">Personas reales con ansiedad real. Sin promesas mágicas — solo
                resultados honestos.</p>

            <div
                style="display: grid; grid-template-columns: 1fr; gap: var(--space-5); max-width: 900px; margin: 0 auto;">
                <div class="testimonial-card reveal reveal--delay-1">
                    <p class="testimonial-card__text">Llevaba 3 años con ataques de pánico. Probé de todo: meditaciones
                        en YouTube, apps, incluso hipnosis. El Método R.E.N.A.C.E. fue lo primero que me hizo ENTENDER
                        por qué me pasaba esto. En 30 días no desapareció del todo, pero aprendí a no tenerle miedo. Y
                        eso lo cambió todo.</p>
                    <div class="testimonial-card__author">
                        <div>
                            <div class="testimonial-card__name">Carolina M., 34 años</div>
                            <div class="testimonial-card__role">Madre de dos hijos · Bogotá, Colombia</div>
                            <div class="testimonial-card__stars">★★★★★</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card reveal reveal--delay-2">
                    <p class="testimonial-card__text">Soy ingeniero de software y mi ansiedad me impedía presentar
                        proyectos. Las manos me temblaban, la voz se me quebraba. Los capítulos de técnicas corporales
                        me dieron herramientas que uso ANTES de cada presentación. Mi jefe notó el cambio antes que yo.
                    </p>
                    <div class="testimonial-card__author">
                        <div>
                            <div class="testimonial-card__name">Andrés R., 29 años</div>
                            <div class="testimonial-card__role">Ingeniero de Software · CDMX, México</div>
                            <div class="testimonial-card__stars">★★★★★</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card reveal reveal--delay-3">
                    <p class="testimonial-card__text">No dormía bien desde hacía más de un año. El Kit de Emergencia fue
                        lo primero que usé — esa misma noche dormí 6 horas seguidas. No es magia, pero las técnicas de
                        respiración del capítulo 6 de verdad funcionan cuando las practicas todos los días.</p>
                    <div class="testimonial-card__author">
                        <div>
                            <div class="testimonial-card__name">Valentina P., 22 años</div>
                            <div class="testimonial-card__role">Estudiante universitaria · Buenos Aires, Argentina</div>
                            <div class="testimonial-card__stars">★★★★☆</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================
         SECCIÓN 8 — BONOS
         ============================================ -->
    <section class="section section--white" id="bonos">
        <div class="container text-center">
            <span class="section__eyebrow reveal">Incluido con tu compra</span>
            <h2 class="section__title reveal">3 Bonos Exclusivos <span class="text-sage">GRATIS</span></h2>
            <p class="section__subtitle reveal">Herramientas complementarias diseñadas para acelerar tu progreso.</p>

            <div
                style="display: grid; grid-template-columns: 1fr; gap: var(--space-5); max-width: 900px; margin: 0 auto;">
                <div class="bonus-card reveal reveal--delay-1">
                    <span class="bonus-card__badge">Bono #1</span>
                    <h4 class="bonus-card__title">Kit de Emergencia Anti-Ansiedad</h4>
                    <p class="bonus-card__desc">Protocolos paso a paso para ataques de pánico, ansiedad nocturna y
                        crisis en público. Tu botiquín emocional de primeros auxilios.</p>
                    <span class="bonus-card__value">Valor: $17 → GRATIS</span>
                </div>

                <div class="bonus-card reveal reveal--delay-2">
                    <span class="bonus-card__badge">Bono #2</span>
                    <h4 class="bonus-card__title">Diario R.E.N.A.C.E. de 30 Días</h4>
                    <p class="bonus-card__desc">Tu compañero diario para registrar progreso, emociones y victorias. Cada
                        día tiene una micro-reflexión guiada.</p>
                    <span class="bonus-card__value">Valor: $19 → GRATIS</span>
                </div>

                <div class="bonus-card reveal reveal--delay-3">
                    <span class="bonus-card__badge">Bono #3</span>
                    <h4 class="bonus-card__title">Recetas y Rutinas Anti-Estrés</h4>
                    <p class="bonus-card__desc">5 recetas anti-inflamatorias + rutinas matutinas y nocturnas diseñadas
                        para calmar tu sistema nervioso.</p>
                    <span class="bonus-card__value">Valor: $15 → GRATIS</span>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================
         SECCIÓN 9 — OFERTA (Stack de Valor)
         ============================================ -->
    <section class="section section--dark" id="oferta">
        <div class="container text-center">
            <span class="section__eyebrow reveal" style="color: var(--sage-green);">Oferta especial</span>
            <h2 class="section__title reveal">Todo lo que recibes hoy</h2>
            <p class="section__subtitle reveal" style="color: rgba(254,252,243,0.7);">Un sistema completo por una
                fracción de lo que cuesta una sola sesión de terapia.</p>

            <div class="value-stack reveal reveal--scale animate-breathe">
                <div class="value-stack__item">
                    <span class="value-stack__check">✅</span>
                    <span class="value-stack__label" style="color: var(--teal-mid);">Ebook "Desactiva Tu Ansiedad" (16
                        capítulos)</span>
                    <span class="value-stack__price">$37</span>
                </div>
                <div class="value-stack__item">
                    <span class="value-stack__check">✅</span>
                    <span class="value-stack__label" style="color: var(--teal-mid);">Plan de 30 Días paso a paso</span>
                    <span class="value-stack__price">$19</span>
                </div>
                <div class="value-stack__item">
                    <span class="value-stack__check">✅</span>
                    <span class="value-stack__label" style="color: var(--teal-mid);">Bono: Kit de Emergencia
                        Anti-Ansiedad</span>
                    <span class="value-stack__price">$17</span>
                </div>
                <div class="value-stack__item">
                    <span class="value-stack__check">✅</span>
                    <span class="value-stack__label" style="color: var(--teal-mid);">Bono: Diario R.E.N.A.C.E.</span>
                    <span class="value-stack__price">$19</span>
                </div>
                <div class="value-stack__item">
                    <span class="value-stack__check">✅</span>
                    <span class="value-stack__label" style="color: var(--teal-mid);">Bono: Recetas y Rutinas
                        Anti-Estrés</span>
                    <span class="value-stack__price">$15</span>
                </div>

                <div class="value-stack__total">
                    <span class="value-stack__original">$107</span>
                    <div>
                        <div style="font-size: var(--text-sm); color: var(--gray-soft); margin-bottom: 4px;">Hoy solo
                        </div>
                        <span class="value-stack__final">$27</span>
                    </div>
                </div>

                <div style="margin-top: var(--space-6); text-align: center;">
                    <a href="<?php echo $checkout_url; ?>" class="btn btn--primary btn--large" id="cta-oferta"
                        style="width: 100%;">
                        <span class="btn__icon">🛒</span> Quiero Empezar Mi Transformación
                    </a>
                    <p style="margin-top: var(--space-3); font-size: var(--text-xs); color: var(--gray-soft);">
                        🔒 Pago seguro · Descarga inmediata · Garantía de 7 días
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================
         SECCIÓN 10 — GARANTÍA
         ============================================ -->
    <section class="section section--cream" id="garantia">
        <div class="container text-center">
            <div class="guarantee-seal reveal">
                <div class="guarantee-seal__icon">🛡️</div>
                <div style="text-align: left;">
                    <div class="guarantee-seal__title">Garantía Total de 7 Días</div>
                    <p class="guarantee-seal__text">Si en los próximos 7 días sientes que este libro no es para ti, te
                        devolvemos cada centavo. Sin preguntas, sin formularios eternos, sin culpa.</p>
                    <p class="guarantee-seal__text"
                        style="margin-top: var(--space-2); color: var(--sage-dark); font-style: italic;">
                        «Prefiero que lo pruebes sin miedo a que te quedes sin la ayuda que necesitas.» — Sofía
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================
         SECCIÓN 11 — FAQ
         ============================================ -->
    <section class="section section--white" id="faq">
        <div class="container container--narrow">
            <div class="text-center">
                <span class="section__eyebrow reveal">Preguntas frecuentes</span>
                <h2 class="section__title reveal">¿Tienes dudas? Es normal.</h2>
            </div>

            <div class="reveal" style="margin-top: var(--space-8);">
                <details class="faq-item">
                    <summary>¿Es un libro digital o físico?</summary>
                    <div class="faq-item__answer">
                        <p>Es un libro 100% digital en formato PDF. Lo recibes al instante por email y puedes leerlo en
                            tu celular, tablet, computadora o imprimirlo si prefieres. Los bonos también son digitales.
                        </p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>¿Funciona si ya he probado otros libros de autoayuda?</summary>
                    <div class="faq-item__answer">
                        <p>El Método R.E.N.A.C.E. no es un listado de tips sueltos. Es un sistema integrado de 6 fases
                            que combina técnicas cognitivas, corporales y de estilo de vida en una secuencia probada. La
                            mayoría de libros te dicen QUÉ hacer — este te dice CÓMO y CUÁNDO, con un plan de 30 días
                            día por día.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>¿Sustituye la terapia profesional?</summary>
                    <div class="faq-item__answer">
                        <p>No. Este libro es un complemento poderoso, no un reemplazo. Si estás en terapia, las
                            herramientas de este libro potenciarán tu proceso. Si no estás en terapia, te dará recursos
                            para manejar tu ansiedad y también una guía honesta (Capítulo 15) sobre cuándo y cómo buscar
                            ayuda profesional.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>¿Cuánto tiempo necesito dedicarle al día?</summary>
                    <div class="faq-item__answer">
                        <p>El plan de 30 días está diseñado para dedicarle entre 15 y 30 minutos diarios. Puedes leer un
                            capítulo completo en una sentada (20-30 minutos) y los ejercicios toman entre 5 y 15
                            minutos.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>¿En qué formato viene? ¿Puedo leerlo en el celular?</summary>
                    <div class="faq-item__answer">
                        <p>Viene en PDF optimizado para lectura digital. Se ve perfecto en cualquier celular, tablet o
                            computadora. También puedes imprimirlo si prefieres leer en papel.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>¿Qué pasa si no tengo tiempo para leer?</summary>
                    <div class="faq-item__answer">
                        <p>Cada capítulo es corto y directo — no es un libro de 500 páginas. Además, puedes ir directo
                            al capítulo que necesites sin leer en orden. Si necesitas ayuda AHORA MISMO, los capítulos
                            6-9 y el Kit de Emergencia son tu punto de partida.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>¿Qué pasa si no me funciona?</summary>
                    <div class="faq-item__answer">
                        <p>Tienes 7 días de garantía total. Si por cualquier motivo sientes que no es para ti, te
                            devolvemos tu dinero completo sin hacer una sola pregunta. El riesgo es cero.</p>
                    </div>
                </details>

                <details class="faq-item">
                    <summary>¿Cómo recibo el libro después del pago?</summary>
                    <div class="faq-item__answer">
                        <p>Inmediatamente después de tu compra recibirás un email con tu enlace de descarga. En menos de
                            2 minutos tendrás el ebook y los 3 bonos en tu dispositivo. Si no ves el email, revisa tu
                            carpeta de spam.</p>
                    </div>
                </details>
            </div>
        </div>
    </section>


    <!-- ============================================
         SECCIÓN 12 — CTA FINAL
         ============================================ -->
    <section class="section section--dark" id="cta-final">
        <div class="container container--narrow text-center">
            <h2 class="section__title reveal" style="color: var(--cream);">
                Hoy puede ser el día en que empieces a recuperar tu paz.
            </h2>
            <p class="reveal"
                style="color: rgba(254,252,243,0.7); font-size: var(--text-md); margin-bottom: var(--space-6);">
                Ebook + Plan de 30 Días + 3 Bonos exclusivos. Todo por <strong
                    style="color: var(--sage-green); font-size: var(--text-xl);">$27</strong>
            </p>
            <div class="reveal">
                <a href="<?php echo $checkout_url; ?>" class="btn btn--primary btn--large" id="cta-final-btn">
                    <span class="btn__icon">🌿</span> Sí, Quiero Mi Paz de Vuelta
                </a>
            </div>
            <p class="reveal"
                style="margin-top: var(--space-4); color: rgba(254,252,243,0.4); font-size: var(--text-xs);">
                Acceso inmediato · Descarga directa · Garantía de 7 días · Pago seguro
            </p>
        </div>
    </section>


    <!-- ============================================
         SECCIÓN 13 — FOOTER
         ============================================ -->
    <footer class="footer">
        <div class="container">
            <p
                style="font-family: var(--font-heading); font-size: var(--text-lg); color: rgba(254,252,243,0.5); margin-bottom: var(--space-4);">
                🌿 Desactiva Tu Ansiedad
            </p>

            <nav class="footer__links">
                <a href="#">Aviso Legal</a>
                <a href="#">Política de Privacidad</a>
                <a href="#">Términos y Condiciones</a>
                <a href="#">Contacto</a>
            </nav>

            <p>© 2025 Sofía Morales. Todos los derechos reservados.</p>

            <p class="footer__disclaimer">
                Este material tiene fines informativos y educativos. No sustituye el diagnóstico, tratamiento o consejo
                de un profesional de la salud mental cualificado. Si experimentas síntomas severos de ansiedad o crisis
                de salud mental, busca ayuda profesional inmediata.
            </p>
        </div>
    </footer>


    <!-- JS -->
    <?php
    $js_path = $theme_dir . '/assets/js/main.js';
    if (file_exists($js_path)) {
        echo '<script src="' . esc_url($theme_uri . '/assets/js/main.js?v=' . filemtime($js_path)) . '" defer></script>';
    }
    ?>

    <?php wp_footer(); ?>
</body>

</html>