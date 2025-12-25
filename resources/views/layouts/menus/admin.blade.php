<li class="pc-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
  <a href="{{ route('admin.dashboard') }}" class="pc-link">
      <span class="pc-micon">
          <svg class="pc-icon">
              <use xlink:href="#custom-status-up"></use>
          </svg>
      </span>
      <span class="pc-mtext">الصفحة الرئيسية</span>
  </a>
</li>

<li class="pc-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
    <a href="{{ route('admin.orders.index') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ph-duotone ph-shopping-cart"></i>
        </span>
        <span class="pc-mtext">الطلبات</span>
    </a>
</li>

<li class="pc-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
    <a href="{{ route('admin.products.index') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ph-duotone ph-package"></i>
        </span>
        <span class="pc-mtext">المنتجات</span>
    </a>
</li>
