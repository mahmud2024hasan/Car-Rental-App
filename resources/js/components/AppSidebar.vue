<script setup>
import NavMain from '@/components/NavMain.vue';
import {Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Car, HandCoins, Users, UserCog, House, PhoneCall, CarFront } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage();
const user = page.props.auth?.user;

// Define the route for the dashboard based on user role
const dashboardRoute = computed(() => {
  if (!user) return route('login');
  if (user.role === 'admin') return route('admin.dashboard');
  if (user.role === 'customer') return route('customer.dashboard');
  return route('/'); // fallback
});

// Define the main navigation items based on user role
const mainNavItems = computed(() => {
  if (!user) return [];

  if (user.role === 'admin') {
    return [
      {
        title: 'Dashboard',
        href: '/admin/dashboard',
        icon: LayoutGrid,
      },
      {
        title: 'Manage Cars',
        href: '/admin/cars',
        icon: Car,
      },
      {
        title: 'Manage Rentals',
        href: '/admin/rentals',
        icon: HandCoins,
      },
      {
        title: 'Manage Customers',
        href: '/admin/customers',
        icon: Users,
      },
      {
        title: 'Manage Admins',
        href: '/admin/users',
        icon: UserCog,
      },
      {
        title: 'Browse Cars',
        href: '/cars',
        icon: CarFront,
      },
      {
        title: 'Home Page',
        href: '/',
        icon: House,
      },

    ];
  }

  if (user.role === 'customer') {
    return [
      {
        title: 'Dashboard',
        href: '/customer/dashboard',
        icon: LayoutGrid,
      },
      {
        title: 'My Bookings',
        href: '/customer/bookings',
        icon: BookOpen,
      },
      {
        title: 'My Profile',
        href: '/customer/profile/settings',
        icon: UserCog,
      },
      {
        title: 'Browse Cars',
        href: '/cars',
        icon: Car,
      },
      {
        title: 'Home Page',
        href: '/',
        icon: House,
      },
      {
        title: 'Contact Us',
        href: '/contact',
        icon: PhoneCall,
      },
      {
        title: 'About Us',
        href: '/about',
        icon: Folder,
      },
    ];
  }

  return [];
});
</script>

<template>
  <Sidebar collapsible="icon" variant="inset">
    <SidebarHeader class="border-b border-sidebar-border/70">
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child>
            <Link :href="dashboardRoute">
              <AppLogo />
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <SidebarContent>
      <NavMain :items="mainNavItems" class="mt-4"/>
    </SidebarContent>
  </Sidebar>
  <slot />
</template>
