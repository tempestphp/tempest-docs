<x-component name="x-falling-leaves">
  <!-- Leaves container -->
  <div class="absolute inset-0 overflow-hidden pointer-events-none motion-reduce:hidden" id="leaves-container"></div>
  <!-- Leaves templates -->
  <template id="leaf-template-1">
    <div class="leaf absolute pointer-events-none origin-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
        <path fill="currentColor" d="M20.998 3v2c0 9.627-5.373 14-12 14H7.096c.212-3.012 1.15-4.835 3.598-7.001c1.204-1.065 1.102-1.68.509-1.327C7.119 13.102 5.09 16.386 5 21.63l-.003.37h-2c0-1.363.116-2.6.346-3.732Q2.999 16.327 2.998 13c0-5.523 4.477-10 10-10c2 0 4 1 8 0" />
      </svg>
    </div>
  </template>
  <template id="leaf-template-2">
    <div class="leaf absolute pointer-events-none origin-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 512 512">
        <path fill="currentColor" d="m150.38 253.68l21.94-23.3l11.65 11c73.63 69.36 147.51 111.56 234.45 133.07c11.73-32 12.77-67.22 2.64-101.58c-13.44-45.59-44.74-85.31-90.49-114.86c-40.25-26-76.6-32.09-115.09-38.54c-21.12-3.54-43-7.2-66.85-14.43c-43.78-13.28-89.69-52.74-90.15-53.13L33.4 30.15L32 63.33c-.1 2.56-2.42 63.57 14.22 147.77c17.58 89 50.24 155.85 97.07 198.63c38 34.69 87.62 53.9 136.93 53.9a186 186 0 0 0 27.78-2.07c41.72-6.32 76.43-27.27 96-57.75c-89.5-23.28-165.95-67.55-242-139.16Z" />
        <path fill="currentColor" d="M467.43 384.19c-16.83-2.59-33.13-5.84-49-9.77a158.5 158.5 0 0 1-12.13 25.68c-.74 1.25-1.51 2.49-2.29 3.71a583 583 0 0 0 58.55 12l15.82 2.44l4.86-31.63Z" />
      </svg>
    </div>
  </template>
  <template id="leaf-template-3">
    <div class="leaf absolute pointer-events-none origin-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 512 512">
        <path d="M453.9 378.7c-51.8-8-55.7-11.7-55.7-11.7 15.6-74-22.4-151.1-76.3-195.6C250.1 112.2 141 155.2 56 65.2c-19.8-21-8.3 235.5 98.1 332.7 77.8 71 169.4 49.2 194.5 37.6 22.8-10.6 38.7-33.9 38.7-33.9 41.5 13 62 14.2 62 14.2 14.6 1.8 22-34.4 4.6-37.1zm-91.8 7.4c-77.7-23.3-145.3-81-189.1-126.2-3.6-3.7 1.6-9.2 5.5-6 43.1 35.5 108.9 80 193.3 107.9.2 8.1-4.5 19.7-9.7 24.3z" fill="currentColor" />
      </svg>
    </div>
  </template>
</x-component>
