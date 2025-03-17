<x-component name="x-rain">
	<!-- Script, See https://github.com/tempestphp/tempest-framework/pull/996 -->
	{{ \Tempest\vite_tags('src/Web/Homepage/rain.ts') }}
  <!-- Rain container -->
  <div class="motion-reduce:hidden absolute inset-0 overflow-hidden pointer-events-none dark:opacity-100 opacity-0 duration-500 z-[-1]" id="rain-container"></div>
</x-component>
