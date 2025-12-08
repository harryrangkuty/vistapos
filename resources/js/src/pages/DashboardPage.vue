<script>
export default {
  props: {
    constant: {
      type: Object,
      default: () => ({}),
    },
  },
};
</script>

<template>
  <div
    class="flex items-center justify-center p-4 bg-gradient-to-r from-blue-200 via-purple-200 to-pink-200 rounded-lg">
    <div class="backdrop-blur-sm bg-white/70 rounded-2xl shadow-lg p-8 max-w-md w-full text-center">
      <h1 class="text-2xl font-bold text-gray-800 mb-2">
        Selamat Datang
      </h1>
      <p class="text-xl text-gray-600 font-medium mb-4">
        {{ constant.user?.name || 'Pengguna' }}
      </p>

      <div v-if="constant.user?.type === 3"
        class="bg-blue-200 text-blue-800 font-semibold py-2 px-4 rounded-full inline-block">
        {{ constant.user?.active_role?.display_name }} Semester {{ constant.user?.semester }}
      </div>

      <div v-else class="bg-blue-200 text-blue-800 font-semibold py-2 px-4 rounded-full inline-block">
        {{ constant.user?.active_role?.display_name }}
      </div>

      <div class="mt-6 flex justify-center gap-4">
        <a v-if="constant.user?.active_role?.name === 'student'" href="/ambil-krs"
          class="flex justify-center items-center gap-x-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:from-blue-600 hover:to-indigo-700 transition inline-block text-center">
          <Icon icon='mdi:file-document-edit-outline' class="text-white" />
          <span>
            Ambil KRS
          </span>
        </a>
        <a v-else-if="['administrator', 'kaprodi', 'sekprodi', 'operator'].includes(constant.user?.active_role?.name)"
          href="/pengajuan-krs"
          class="flex justify-center items-center gap-x-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:from-blue-600 hover:to-indigo-700 transition inline-block text-center">
          <Icon icon="mdi:file-document-edit-outline" class="text-white" />
          <span>Lihat Pengajuan KRS Mahasiswa</span>
        </a>
      </div>
    </div>
  </div>
</template>
