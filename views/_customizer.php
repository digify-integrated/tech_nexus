<div class="pct-c-btn">
        <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_pc_layout">
            <svg class="pc-icon"><use xlink:href="#custom-setting-2"></use></svg>
        </a>
    </div>
    <div class="offcanvas border-0 pct-offcanvas offcanvas-end" tabindex="-1" id="offcanvas_pc_layout">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Settings</h5>
            <button type="button" class="btn btn-icon btn-link-danger" data-bs-dismiss="offcanvas" aria-label="Close"><i class="ti ti-x"></i></button>
        </div>
        <div class="pct-body" style="height: calc(100% - 85px)">
            <div class="offcanvas-body py-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                <div class="pc-dark">
                    <h6 class="mb-1">Theme Mode</h6>
                    <p class="text-muted text-sm">Choose light or dark mode or Auto</p>
                    <div class="row theme-layout">
                    <div class="col-6">
                        <div class="d-grid">
                        <button class="preset-btn btn active" data-value="true" id="layout_light">
                            <svg class="pc-icon text-warning">
                            <use xlink:href="#custom-sun-1"></use>
                            </svg>
                        </button>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-grid">
                        <button class="preset-btn btn" data-value="false" id="layout_dark">
                            <svg class="pc-icon">
                            <use xlink:href="#custom-moon"></use>
                            </svg>
                        </button>
                        </div>
                    </div>
                    </div>
                </div>
                </li>
                <li class="list-group-item">
                <h6 class="mb-1">Theme Contrast</h6>
                <p class="text-muted text-sm">Choose theme contrast</p>
                <div class="row theme-contrast">
                    <div class="col-6">
                    <div class="d-grid">
                        <button class="preset-btn btn" data-value="true" id="light_contrast">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-mask"></use>
                        </svg>
                        </button>
                    </div>
                    </div>
                    <div class="col-6">
                    <div class="d-grid">
                        <button class="preset-btn btn active" data-value="false" id="dark_contrast">
                        <svg class="pc-icon">
                            <use xlink:href="#custom-mask-1-outline"></use>
                        </svg>
                        </button>
                    </div>
                    </div>
                </div>
                </li>
                <li class="list-group-item">
                <h6 class="mb-1">Custom Theme</h6>
                <p class="text-muted text-sm">Choose your Primary color</p>
                <div class="theme-color preset-color">
                    <a href="JavaScript:void(0);" class="active" data-value="preset-1" id="preset_1"><i class="ti ti-check"></i></a>
                    <a href="JavaScript:void(0);" data-value="preset-2" id="preset_2"><i class="ti ti-check"></i></a>
                    <a href="JavaScript:void(0);" data-value="preset-3" id="preset_3"><i class="ti ti-check"></i></a>
                    <a href="JavaScript:void(0);" data-value="preset-4" id="preset_4"><i class="ti ti-check"></i></a>
                    <a href="JavaScript:void(0);" data-value="preset-5" id="preset_5"><i class="ti ti-check"></i></a>
                    <a href="JavaScript:void(0);" data-value="preset-6" id="preset_6"><i class="ti ti-check"></i></a>
                    <a href="JavaScript:void(0);" data-value="preset-7" id="preset_7"><i class="ti ti-check"></i></a>
                    <a href="JavaScript:void(0);" data-value="preset-8" id="preset_8"><i class="ti ti-check"></i></a>
                    <a href="JavaScript:void(0);" data-value="preset-9" id="preset_9"><i class="ti ti-check"></i></a>
                    <a href="JavaScript:void(0);" data-value="preset-10" id="preset_10"><i class="ti ti-check"></i></a>
                </div>
                </li>
                <li class="list-group-item">
                <h6 class="mb-1">Sidebar Caption</h6>
                <p class="text-muted text-sm">Sidebar Caption Hide/Show</p>
                <div class="row theme-nav-caption">
                    <div class="col-6">
                    <div class="d-grid">
                        <button class="preset-btn btn active" data-value="true" id="show_caption">
                        <img src="./assets/images/customizer/img-caption-1.svg" alt="img" class="img-fluid" width="70%" />
                        </button>
                    </div>
                    </div>
                    <div class="col-6">
                    <div class="d-grid">
                        <button class="preset-btn btn" data-value="false" id="hide_caption">
                        <img src="./assets/images/customizer/img-caption-2.svg" alt="img" class="img-fluid" width="70%" />
                        </button>
                    </div>
                    </div>
                </div>
                </li>
                <li class="list-group-item">
                <div class="pc-rtl">
                    <h6 class="mb-1">Theme Layout</h6>
                    <p class="text-muted text-sm">LTR/RTL</p>
                    <div class="row theme-direction">
                    <div class="col-6">
                        <div class="d-grid">
                        <button class="preset-btn btn active" data-value="false" id="ltr">
                            <img src="./assets/images/customizer/img-layout-1.svg" alt="img" class="img-fluid" width="70%" />
                        </button>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-grid">
                        <button class="preset-btn btn" data-value="true" id="rtl">
                            <img src="./assets/images/customizer/img-layout-2.svg" alt="img" class="img-fluid" width="70%" />
                        </button>
                        </div>
                    </div>
                    </div>
                </div>
                </li>
                <li class="list-group-item">
                <div class="pc-container-width">
                    <h6 class="mb-1">Layout Width</h6>
                    <p class="text-muted text-sm">Choose Full or Container Layout</p>
                    <div class="row theme-container">
                    <div class="col-6">
                        <div class="d-grid">
                        <button class="preset-btn btn active" data-value="false" id="full_layout">
                            <img src="./assets/images/customizer/img-container-1.svg" alt="img" class="img-fluid" width="70%" />
                        </button>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-grid">
                        <button class="preset-btn btn" data-value="true" id="boxed_layout">
                            <img src="./assets/images/customizer/img-container-2.svg" alt="img" class="img-fluid" width="70%" />
                        </button>
                        </div>
                    </div>
                    </div>
                </div>
                </li>
            </ul>
            </div>
        </div>
    </div>