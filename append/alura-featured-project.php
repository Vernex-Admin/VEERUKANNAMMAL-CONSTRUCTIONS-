<?php
$aluraContext = isset($aluraContext) ? $aluraContext : 'home';
$aluraIsProjectsPage = $aluraContext === 'projects';
$aluraSectionId = $aluraIsProjectsPage ? 'alura-apartments' : 'featured-alura-apartments';
$aluraExploreHref = $aluraIsProjectsPage ? '#alura-apartments-details' : 'projects.php#alura-apartments';
$aluraWalkthroughId = 'alura-walkthrough-dialog-' . ($aluraIsProjectsPage ? 'projects' : 'home');
?>
<section id="<?= htmlspecialchars($aluraSectionId, ENT_QUOTES, 'UTF-8'); ?>" class="section-full alura-feature-section" aria-labelledby="alura-project-title-<?= $aluraIsProjectsPage ? 'projects' : 'home'; ?>">
    <div class="container">
        <div class="alura-feature-lead">
            <div class="alura-gallery" aria-label="Alura Apartments project images">
                <figure class="alura-gallery-main">
                    <a class="alura-image-open" href="images/projects/alura/alura-exterior.jpg" target="_blank" rel="noopener noreferrer" aria-label="Open the Alura Apartments exterior rendering at full size in a new tab">
                        <img src="images/projects/alura/alura-exterior.jpg" width="1141" height="676" alt="Exterior rendering of Alura Apartments" loading="eager" fetchpriority="high" decoding="async">
                        <span class="alura-image-open-label" aria-hidden="true"><i class="fa fa-search-plus"></i> View full image</span>
                    </a>
                    <figcaption>Alura Apartments · exterior rendering</figcaption>
                </figure>
                <figure class="alura-gallery-inset">
                    <a class="alura-image-open" href="images/projects/alura/alura-living-room.jpg" target="_blank" rel="noopener noreferrer" aria-label="Open the Alura Apartments interior rendering at full size in a new tab">
                        <img src="images/projects/alura/alura-living-room.jpg" width="701" height="561" alt="Furnished living and dining interior rendering" loading="lazy" decoding="async">
                        <span class="alura-image-open-label" aria-hidden="true"><i class="fa fa-search-plus"></i> View full image</span>
                    </a>
                    <figcaption>Interior view</figcaption>
                </figure>
                <figure class="alura-gallery-inset alura-gallery-kitchen">
                    <a class="alura-image-open" href="images/projects/alura/alura-kitchen.jpg" target="_blank" rel="noopener noreferrer" aria-label="Open the Alura Apartments kitchen rendering at full size in a new tab">
                        <img src="images/projects/alura/alura-kitchen.jpg" width="701" height="561" alt="Alura Apartments kitchen rendering" loading="lazy" decoding="async">
                        <span class="alura-image-open-label" aria-hidden="true"><i class="fa fa-search-plus"></i> View full image</span>
                    </a>
                    <figcaption>Kitchen</figcaption>
                </figure>
            </div>

            <div class="alura-project-intro">
                <p class="alura-eyebrow"><span class="alura-status-dot" aria-hidden="true"></span> Ongoing project · Veeru Kannammal Construction</p>
                <h2 id="alura-project-title-<?= $aluraIsProjectsPage ? 'projects' : 'home'; ?>">Alura Apartments</h2>
                <p class="alura-location"><i class="fa fa-map-marker" aria-hidden="true"></i> Valasaravakkam, Chennai</p>
                <p class="alura-address">Plot No. 14 &amp; 15, AKR Nagar, 4th Street, Valasaravakkam, Chennai – 600087</p>
                <p class="alura-summary">A fully furnished 3 BHK apartment project, East/North facing and 100% Vasthu compliant.</p>

                <div class="alura-highlights" aria-label="Project highlights">
                    <div class="alura-highlight"><strong>3,773</strong><span>sq.ft. land area</span></div>
                    <div class="alura-highlight"><strong>1,599</strong><span>sq.ft. saleable area</span></div>
                    <div class="alura-highlight"><strong>628.83</strong><span>sq.ft. UDS</span></div>
                    <div class="alura-highlight"><strong>3</strong><span>floors</span></div>
                </div>

                <div class="alura-actions">
                    <a class="alura-button alura-button-primary" href="<?= htmlspecialchars($aluraExploreHref, ENT_QUOTES, 'UTF-8'); ?>">
                        Explore Alura Apartments <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                    </a>
                    <button class="alura-button alura-button-secondary" type="button" data-alura-walkthrough-trigger data-walkthrough-target="<?= htmlspecialchars($aluraWalkthroughId, ENT_QUOTES, 'UTF-8'); ?>" aria-controls="<?= htmlspecialchars($aluraWalkthroughId, ENT_QUOTES, 'UTF-8'); ?>" aria-expanded="false">
                        View 3D Walkthrough <i class="fa fa-cube" aria-hidden="true"></i>
                    </button>
                    <?php if ($aluraIsProjectsPage): ?>
                    <button class="alura-button alura-button-secondary alura-button-brochure" type="button" data-alura-brochure-trigger aria-controls="alura-brochure-dialog-projects">
                        View Brochure <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="alura-information-grid" id="alura-apartments-details">
            <article class="alura-information-card">
                <span class="alura-card-kicker">01 · Specifications</span>
                <h3>Designed details</h3>
                <ul>
                    <li>RCC framed structure with pile foundation</li>
                    <li>9-inch outer walls and 4.5-inch inner walls</li>
                    <li>11 ft ceiling height</li>
                    <li>Individual water meter</li>
                    <li>East/North facing · 100% Vasthu compliant</li>
                </ul>
            </article>

            <article class="alura-information-card">
                <span class="alura-card-kicker">02 · Facilities</span>
                <h3>Everyday comfort</h3>
                <ul>
                    <li>Premium 6-person lift and generator backup</li>
                    <li>Individual covered car parking with EV charging provision</li>
                    <li>Access-controlled entry and 24/7 CCTV surveillance</li>
                    <li>Terrace seating area and two-side ventilation</li>
                    <li>Automatic water-level control</li>
                    <li>3-phase power with manual changeover</li>
                </ul>
            </article>

            <article class="alura-information-card alura-location-card">
                <span class="alura-card-kicker">03 · Nearby landmarks</span>
                <h3>Valasaravakkam, Chennai</h3>
                <ul class="alura-landmark-list">
                    <li><span>Sri Chaitanya Techno School</span><strong>200 m</strong></li>
                    <li><span>Madha Public CBSE School</span><strong>300 m</strong></li>
                    <li><span>Nexus Vijaya Mall</span><strong>4 km</strong></li>
                    <li><span>Vadapalani Metro Station</span><strong>4 km</strong></li>
                </ul>
                <p class="alura-landmark-note">Near playback singer Mano’s residence.</p>
            </article>
        </div>

        <div class="alura-floorplan-card">
            <div class="alura-floorplan-copy">
                <span class="alura-card-kicker">From the project brochure</span>
                <h3>Third floor plan</h3>
                <p>The brochure lists a living and dining area, three bedrooms, kitchen, utility, balconies and lift.</p>
                <p class="alura-plan-note">Dimensions shown are indicative; verify them against final drawings and site measurements.</p>
            </div>
            <figure class="alura-floorplan-image">
                <a class="alura-image-open" href="images/projects/alura/alura-floor-plan.jpg" target="_blank" rel="noopener noreferrer" aria-label="Open the Alura Apartments floor plan at full size in a new tab">
                    <img src="images/projects/alura/alura-floor-plan.jpg" width="1280" height="1264" alt="Alura Apartments third floor plan from the project brochure" loading="lazy" decoding="async">
                    <span class="alura-image-open-label" aria-hidden="true"><i class="fa fa-search-plus"></i> View full plan</span>
                </a>
            </figure>
        </div>

        <p class="alura-inclusions">The brochure notes that car parking, EB connection, water bore and generator backup are included. GST and registration are payable separately.</p>

        <dialog class="alura-walkthrough-dialog" id="<?= htmlspecialchars($aluraWalkthroughId, ENT_QUOTES, 'UTF-8'); ?>" aria-labelledby="alura-walkthrough-title-<?= $aluraIsProjectsPage ? 'projects' : 'home'; ?>">
            <div class="alura-walkthrough-dialog-content">
                <header class="alura-walkthrough-dialog-header">
                    <div>
                        <span class="alura-card-kicker">Interactive 360° tour</span>
                        <h2 id="alura-walkthrough-title-<?= $aluraIsProjectsPage ? 'projects' : 'home'; ?>">Alura Apartments</h2>
                    </div>
                    <button class="alura-walkthrough-close" type="button" data-alura-walkthrough-close aria-label="Close 3D walkthrough" autofocus>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </header>
                <div class="alura-walkthrough-viewport">
                    <iframe title="Interactive Alura Apartments 360 degree walkthrough" data-alura-walkthrough-frame data-walkthrough-mount="alura-apartments" data-src="walkthroughs/alura/index.html?v=20261105g" allow="fullscreen; accelerometer; gyroscope; xr-spatial-tracking" allowfullscreen></iframe>
                    <p class="alura-walkthrough-status" data-walkthrough-status role="status" aria-live="polite" hidden></p>
                    <div class="alura-walkthrough-error" data-walkthrough-error hidden>
                        <p>The walkthrough could not be loaded. Check your connection and try again.</p>
                        <div class="alura-walkthrough-error-actions">
                            <button class="alura-button alura-button-primary" type="button" data-alura-walkthrough-retry>Retry walkthrough</button>
                            <a class="alura-button alura-button-secondary" href="walkthroughs/alura/index.html?v=20261105g" target="_blank" rel="noopener noreferrer">Open in a new tab</a>
                        </div>
                    </div>
                </div>
                <nav class="alura-room-selector" aria-label="Choose a room in the Alura Apartments walkthrough">
                    <div class="alura-room-selector-heading">
                        <span>Choose a room</span>
                        <span class="alura-room-selector-count">12 rooms and spaces</span>
                    </div>
                    <div class="alura-room-selector-navigation">
                        <button class="alura-room-selector-step" type="button" data-alura-scene-step="-1" aria-label="Previous room" disabled><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                        <div class="alura-room-selector-options">
                        <button type="button" data-alura-scene-index="0" data-alura-scene-name="01-Lobby" aria-label="View Lobby" disabled><img src="walkthroughs/alura/media/panorama_0E9CD6C5_0518_EB64_4192_3991F0D79E2C_t.jpg" width="98" height="55" alt="" loading="lazy" decoding="async"><span>Lobby</span></button>
                        <button type="button" data-alura-scene-index="1" data-alura-scene-name="02-Living" aria-label="View Living room" disabled><img src="walkthroughs/alura/media/panorama_0F7C2571_0518_E93C_4190_596A18EED3AD_t.jpg" width="98" height="55" alt="" loading="lazy" decoding="async"><span>Living room</span></button>
                        <button type="button" data-alura-scene-index="2" data-alura-scene-name="03-Living 01" aria-label="View Living room 01" disabled><img src="walkthroughs/alura/media/panorama_0F699910_0518_E6FC_416E_3BFBCDA2AA50_t.jpg" width="98" height="55" alt="" loading="lazy" decoding="async"><span>Living room 01</span></button>
                        <button type="button" data-alura-scene-index="3" data-alura-scene-name="04-Kitchen" aria-label="View Kitchen" disabled><img src="walkthroughs/alura/media/panorama_0F6AFC93_0518_9FE3_417A_88F3FBBA652A_t.jpg" width="98" height="55" alt="" loading="lazy" decoding="async"><span>Kitchen</span></button>
                        <button type="button" data-alura-scene-index="4" data-alura-scene-name="05-Living Balcony" aria-label="View Living room balcony" disabled><img src="walkthroughs/alura/media/panorama_0F762005_0518_A6E4_417A_2E81068A0FD1_t.jpg" width="98" height="55" alt="" loading="lazy" decoding="async"><span>Living balcony</span></button>
                        <button type="button" data-alura-scene-index="5" data-alura-scene-name="06-Common Toilet" aria-label="View Common bathroom" disabled><img src="walkthroughs/alura/media/panorama_0F63E3BA_0518_A92D_4170_2AD63332A149_t.jpg" width="98" height="55" alt="" loading="lazy" decoding="async"><span>Common bathroom</span></button>
                        <button type="button" data-alura-scene-index="6" data-alura-scene-name="07-Utility" aria-label="View Utility area" disabled><img src="walkthroughs/alura/media/panorama_0F766740_0518_A95D_418C_5A932AAB768C_t.jpg" width="98" height="55" alt="" loading="lazy" decoding="async"><span>Utility</span></button>
                        <button type="button" data-alura-scene-index="7" data-alura-scene-name="08-Bedroom-1" aria-label="View Bedroom 1" disabled><img src="walkthroughs/alura/media/panorama_185E116A_13F3_9AA9_4180_F26C8E8F9D55_t.jpg" width="98" height="55" alt="" loading="lazy" decoding="async"><span>Bedroom 1</span></button>
                        <button type="button" data-alura-scene-index="8" data-alura-scene-name="09-Bedroom-2" aria-label="View Bedroom 2" disabled><img src="walkthroughs/alura/media/panorama_0F64BF0D_0518_BAE7_4154_8EDF5AA1BCEA_t.jpg" width="98" height="55" alt="" loading="lazy" decoding="async"><span>Bedroom 2</span></button>
                        <button type="button" data-alura-scene-index="9" data-alura-scene-name="10-Bedroom-2 Balcony" aria-label="View Bedroom 2 balcony" disabled><img src="walkthroughs/alura/media/panorama_0F66F352_0518_A97D_4170_3D15EE18753C_t.jpg" width="98" height="55" alt="" loading="lazy" decoding="async"><span>Bedroom 2 balcony</span></button>
                        <button type="button" data-alura-scene-index="10" data-alura-scene-name="11-Bedroom-3" aria-label="View Bedroom 3" disabled><img src="walkthroughs/alura/media/panorama_0F64F717_0518_AAE3_417A_7ADA038AF6D7_t.jpg" width="98" height="55" alt="" loading="lazy" decoding="async"><span>Bedroom 3</span></button>
                        <button type="button" data-alura-scene-index="11" data-alura-scene-name="12-Bedroom-3 Toilet" aria-label="View Bedroom 3 bathroom" disabled><img src="walkthroughs/alura/media/panorama_0F62FACB_0518_9B63_4185_F0CCA7BFA7D1_t.jpg" width="98" height="55" alt="" loading="lazy" decoding="async"><span>Bedroom 3 bathroom</span></button>
                        </div>
                        <button class="alura-room-selector-step" type="button" data-alura-scene-step="1" aria-label="Next room" disabled><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                    </div>
                </nav>
                <footer class="alura-walkthrough-dialog-footer">
                    <p>Use touch or mouse controls inside the viewer to explore.</p>
                </footer>
            </div>
        </dialog>

        <?php if ($aluraIsProjectsPage): ?>
        <dialog class="alura-brochure-dialog" id="alura-brochure-dialog-projects" aria-labelledby="alura-brochure-title-projects">
            <div class="alura-brochure-dialog-content">
                <header class="alura-brochure-dialog-header">
                    <div>
                        <span class="alura-card-kicker">Project brochure</span>
                        <h2 id="alura-brochure-title-projects">Alura Apartments</h2>
                    </div>
                    <button class="alura-brochure-close" type="button" data-alura-brochure-close aria-label="Close brochure preview" autofocus>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </header>
                <div class="alura-brochure-viewer">
                    <iframe title="Alura Apartments project brochure" data-src="documents/alura-apartments-brochure.pdf#view=FitH" loading="lazy"></iframe>
                </div>
                <footer class="alura-brochure-dialog-footer">
                    <p>View the brochure here or download a copy to your device.</p>
                    <a class="alura-button alura-button-primary" href="documents/alura-apartments-brochure.pdf" download="Alura-Apartments-Brochure.pdf">
                        Download Brochure <i class="fa fa-download" aria-hidden="true"></i>
                    </a>
                </footer>
            </div>
        </dialog>
        <?php endif; ?>
    </div>
</section>
