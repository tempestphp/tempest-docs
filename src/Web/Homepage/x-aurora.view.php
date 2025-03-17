<x-component name="x-aurora">
  <div class="absolute inset-0 overflow-hidden pointer-events-none z-[-1]" :class="$class">
    <div class=" 
          [--color-white-gradient:repeating-linear-gradient(100deg,white_0%,white_7%,transparent_10%,transparent_12%,white_16%)]
          [--color-aurora-gradient:repeating-linear-gradient(100deg,var(--color-aurora-1)_10%,var(--color-aurora-2)_15%,var(--color-aurora-3)_20%,var(--color-aurora-4)_25%,var(--color-aurora-5)_30%)]
          [background-image:var(--color-aurora-gradient)]
          ___motion-reduce:[background-image:var(--color-aurora-gradient)]
          ___motion-safe:[background-image:var(--color-white-gradient),var(--color-aurora-gradient)]
          [background-size:300%,_200%]
          motion-safe:[background-position:50%_50%,50%_50%]
          [background-position:50%_50%,50%_50%]
          filter
          blur-[10px]
          invert
          after:content-['']
          after:absolute
          after:inset-0
          ___motion-reduce:after:[background-image:var(--color-aurora-gradient)]
          ___motion-safe:after:[background-image:var(--color-white-gradient),var(--color-aurora-gradient)]
          after:[background-image:var(--color-white-gradient),var(--color-aurora-gradient)]
          after:[background-size:200%,_100%]
          ___motion-safe:after:animate-aurora
          after:animate-aurora
          after:[background-attachment:fixed]
          after:mix-blend-difference
          pointer-events-none
          absolute
          inset-0
          opacity-50
					after:will-change-auto
          will-change-auto
          [mask-image:radial-gradient(ellipse_at_100%_0%,black_10%,transparent_70%)]
      "></div>
  </div>
</x-component>
