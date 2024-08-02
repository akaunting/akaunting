{pkgs}: {
  deps = [
    pkgs.haskellPackages.options-time
    pkgs.img2pdf
    pkgs.python312Packages.xstatic-font-awesome
    pkgs.python311Packages.mkdocs
    pkgs.perlPackages.HTMLFormatExternal
  ];
}
