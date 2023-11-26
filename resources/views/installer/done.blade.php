@extends('installer.master')
@section('content')
<!--begin::Root-->
<div class="d-flex flex-column flex-root">
    <!--begin::Authentication - Multi-steps-->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid stepper stepper-pills stepper-column" id="kt_create_account_stepper">
        <!--begin::Aside-->
        <div class="d-flex flex-column flex-lg-row-auto w-xl-500px bg-lighten shadow-sm">
            <!--begin::Wrapper-->
            <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-500px scroll-y">
                <!--begin::Header-->
                <div class="d-flex flex-row-fluid flex-column flex-center p-10 pt-lg-20">
         
                    <!--begin::Nav-->
                    <div class="stepper-nav">
                        <!--begin::Step 1-->
                        <div class="stepper-item " data-kt-stepper-element="nav">
                            <!--begin::Line-->
                            <div class="stepper-line w-40px"></div>
                            <!--end::Line-->
                            <!--begin::Icon-->
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">1</span>
                            </div>
                            <!--end::Icon-->
                            <!--begin::Label-->
                            <div class="stepper-label">
                                <h3 class="stepper-title">Welcome</h3>

                            </div>
                            <!--end::Label-->
                        </div>
                        <!--end::Step 1-->
                        <!--begin::Step 2-->
                        <div class="stepper-item " data-kt-stepper-element="nav">
                            <!--begin::Line-->
                            <div class="stepper-line w-40px"></div>
                            <!--end::Line-->
                            <!--begin::Icon-->
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">2</span>
                            </div>
                            <!--end::Icon-->
                            <!--begin::Label-->
                            <div class="stepper-label">
                                <h3 class="stepper-title">Check system requirements</h3>
                            </div>
                            <!--end::Label-->
                        </div>
                        <!--end::Step 2-->
                        <!--begin::Step 3-->
                        <div class="stepper-item " data-kt-stepper-element="nav">
                            <!--begin::Line-->
                            <div class="stepper-line w-40px"></div>
                            <!--end::Line-->
                            <!--begin::Icon-->
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">3</span>
                            </div>
                            <!--end::Icon-->
                            <!--begin::Label-->
                            <div class="stepper-label">
                                <h3 class="stepper-title">License verification</h3>
                                <div class="stepper-desc fw-bold">Verify your purchase license</div>
                            </div>
                            <!--end::Label-->
                        </div>
                        <!--end::Step 3-->
                        <!--begin::Step 4-->
                        <div class="stepper-item" data-kt-stepper-element="nav">
                            <!--begin::Line-->
                            <div class="stepper-line w-40px"></div>
                            <!--end::Line-->
                            <!--begin::Icon-->
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">4</span>
                            </div>
                            <!--end::Icon-->
                            <!--begin::Label-->
                            <div class="stepper-label">
                                <h3 class="stepper-title">Check your database</h3>
                                <div class="stepper-desc fw-bold">Setup your database</div>
                            </div>
                            <!--end::Label-->
                        </div>
                        <!--end::Step 4-->
                        <!--begin::Step 5-->
                        <div class="stepper-item" data-kt-stepper-element="nav">
                            <!--begin::Line-->
                            <div class="stepper-line w-40px"></div>
                            <!--end::Line-->
                            <!--begin::Icon-->
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">5</span>
                            </div>
                            <!--end::Icon-->
                            <!--begin::Label-->
                            <div class="stepper-label">
                                <h3 class="stepper-title">Install</h3>
                            </div>
                            <!--end::Label-->
                        </div>
                        <!--end::Step 5-->
                        <!--begin::Step 6-->
                        <div class="stepper-item" data-kt-stepper-element="nav">
                            <!--begin::Line-->
                            <div class="stepper-line w-40px"></div>
                            <!--end::Line-->
                            <!--begin::Icon-->
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">6</span>
                            </div>
                            <!--end::Icon-->
                            <!--begin::Label-->
                            <div class="stepper-label">
                                <h3 class="stepper-title">Admin setup</h3>
                                <div class="stepper-desc fw-bold">Setup your admin details</div>

                            </div>
                            <!--end::Label-->
                        </div>
                        <!--end::Step 6-->
                        <!--begin::Step 6-->
                        <div class="stepper-item current" data-kt-stepper-element="nav">
                            <!--begin::Line-->
                            <div class="stepper-line w-40px"></div>
                            <!--end::Line-->
                            <!--begin::Icon-->
                            <div class="stepper-icon w-40px h-40px">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">7</span>
                            </div>
                            <!--end::Icon-->
                            <!--begin::Label-->
                            <div class="stepper-label">
                                <h3 class="stepper-title">Done</h3>
                            </div>
                            <!--end::Label-->
                        </div>
                        <!--end::Step 6-->
                    </div>
                    <!--end::Nav-->
                </div>
                <!--end::Header-->

            </div>
            <!--end::Wrapper-->
        </div>
        <!--begin::Aside-->
        <!--begin::Body-->
        <div class="d-flex flex-column flex-lg-row-fluid py-10">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid">
                <!--begin::Wrapper-->
                <div class="w-lg-700px p-10 p-lg-15 mx-auto">
                    <!--begin::Form-->

                    <!--begin::Step 1-->
                    <div class="current" data-kt-stepper-element="content">
                        <!--begin::Wrapper-->
                        <div class="w-100">
                            <!--begin::Heading-->
                            <div class="pb-10 pb-lg-15">
                                <!--begin::Title-->
                                <h2 class="fw-bolder d-flex align-items-center text-dark">Welcome to Investpro
                                </h2>
                                <!--end::Title-->
                                <!--begin::Notice-->
                                <div class="text-muted fw-bold fs-6 pt-3">Your Installation is complete. Enjoy your app. Thank you.

                                </div>
                                <!--end::Notice-->
                            </div>
                            <!--end::Heading-->

                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Step 1-->

                    <!--begin::Actions-->
                    <div class="d-flex flex-stack pt-15">

                        <div>

                            <a type="button" href="{{url('/')}}" class="btn btn-lg btn-primary">Go to home
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                                <span class="svg-icon svg-icon-4 ms-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
                                        <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </a>
                        </div>
                    </div>
                    <!--end::Actions-->

                    <!--end::Form-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Content-->
            <!--begin::Footer-->
            <div class="d-flex flex-center flex-wrap fs-6 p-5 pb-0">
                <!--begin::Links-->
                <div class="d-flex flex-center fw-bold fs-6">
                    <a href="mailto:" class="text-muted text-hover-primary px-2" target="_blank">Support</a>
                    <a href="" class="text-muted text-hover-primary px-2" target="_blank">Purchase</a>
                </div>
                <!--end::Links-->
            </div>
            <!--end::Footer-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Authentication - Multi-steps-->
</div>
<!--end::Root-->


@endsection