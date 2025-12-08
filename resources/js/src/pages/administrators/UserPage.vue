<script>

const sortDirections = ['ascend', 'descend'];
const columns = [
  {
    title: "#",
    key: "number",
    align: "center",
    width: 50,
  },
  {
    title: "NIK",
    dataIndex: "identifier",
    width: 120,
    ellipsis: true,
    sorter: (a, b) => (a.identifier || '').localeCompare(b.identifier || ''),
    sortDirections,
  },
  {
    title: "Nama Pengguna",
    dataIndex: "name",
    width: 200,
    ellipsis: true,
    sorter: (a, b) => (a.name || '').localeCompare(b.name || ''),
    sortDirections,
  },
  {
    title: "Divisi",
    dataIndex: "division",
    width: 150,
    ellipsis: true,
    sorter: (a, b) => (a.division || '').localeCompare(b.division || ''),
    sortDirections,
  },
  {
    title: "Departemen",
    dataIndex: "department",
    width: 180,
    ellipsis: true,
    sorter: (a, b) => (a.department || '').localeCompare(b.department || ''),
    sortDirections,
  },
  {
    title: "Jabatan",
    key: "position",
    width: 180,
    ellipsis: true,
    sorter: (a, b) => (a.position || '').localeCompare(b.position || ''),
    sortDirections,
  },
  {
    title: "Email",
    dataIndex: "email",
    width: 140,
    ellipsis: true,
    sorter: (a, b) => (a.email || '').localeCompare(b.email || ''),
    sortDirections,
  },
  {
    title: "Hak Akses",
    key: "roles",
    width: 140,
    ellipsis: true,
    sorter: (a, b) => {
      const ra = Array.isArray(a.roles)
        ? a.roles.map(r => r.display_name ?? r).join(', ')
        : (a.roles || '');
      const rb = Array.isArray(b.roles)
        ? b.roles.map(r => r.display_name ?? r).join(', ')
        : (b.roles || '');
      return ra.localeCompare(rb);
    },
    sortDirections,
  },
  {
    title: "Action",
    key: "action",
    align: "center",
    width: 110,
    fixed: 'right',
    className: 'column-action'
  },
];
export default {
  props: {
    title: String,
    constant: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      columns,
      form: {
        id: null,
        identifier: null,
      },
      filter: {
        status: "aktif",
        roles: [],
      },
    };
  },

  mounted() {
    this.readData();
  },

  methods: {
    async readData(v) {
      const vm = this;
      vm.loadingTrue();
      let params = v ?? {
        total: vm._pagination.total,
        page: vm._pagination.current,
        results: vm._pagination.pageSize,
      };

      params = {
        req: "table",
        ...params,
        ...vm.filter,
      };

      const response = await vm.axios.get(vm.readRoute, { params });
      if (response && response.data) {
        const pagination = { ...vm._pagination };
        pagination.total = response.data.models.total;
        vm.loadingFalse();
        vm.models = response.data.models.data;
        vm._pagination = pagination;
      }
    },

    editData(m) {
      const vm = this;
      vm.form = vm.lodash.cloneDeep(m);
      if (Array.isArray(vm.form.roles)) {
        vm.form.roles = vm.form.roles.map(role => role.id);
      } else {
        vm.form.roles = [];
      }
      vm.$nextTick(function () {
        vm.showModal = true;
      })
    },

    async writeData() {
      const vm = this;
      vm.loadingTrue();
      const form = {
        req: 'write',
        ...vm.form
      };
      const response = await vm.axios.post(vm.writeRoute, form).catch((e) => vm.$onAjaxError(e));
      if (response && response.data) {
        vm.showModal = false;
        vm.readData();
        vm.loadingFalse();
        vm.openNotification(vm.form.id ? 'Berhasil mengubah data' : 'Berhasil menyimpan data', 'success');
      }
    },

    async deleteData(id, req) {
      const vm = this;
      const param = { req, id };
      const response = await vm.axios
        .post(vm.writeRoute, param)
        .catch((error) => vm.$onAjaxError(error));

      if (response && response.data) {
        vm.readData();
        if (req == "restore") {
          vm.openNotification("Data berhasil dikembalikan", "success");
        } else {
          vm.openNotification("Data berhasil dihapus", "success");
        }
      }
    },

    async syncData() {
      const vm = this;
      vm.loadingTrue();

      try {
        const response = await vm.axios.get(vm.readRoute, {
          params: { req: "sync" }
        });

        if (response && response.data) {
          vm.openNotification("Sinkronisasi berhasil!", "success");
          vm.readData();
        }
      } catch (e) {
        vm.$onAjaxError(e);
      } finally {
        vm.loadingFalse();
      }
    },

    newData() {
      const vm = this;
      vm.form = vm.$options.data().form;
      vm.$nextTick(function () {
        vm.showModal = true;
      })
    },
  },
};
</script>

<template>
  <a-card class="card">
    <a-row class="flex flex-wrap items-start justify-between mb-4 pb-4 border-b-2 gap-y-4">
      <a-col :xs="24" :sm="24" :md="6">
        <h1 class="text-base font-semibold">
          {{ title }}
        </h1>
      </a-col>
      <a-col :xs="24" :sm="24" :md="18" class="flex justify-end">
        <a-row class="flex flex-wrap gap-2 justify-start md:justify-end w-full md:w-auto">
          <a-col class="w-full md:w-auto">
            <a-select v-model:value="filter.status" class="min-w-32 lg:w-32 w-full" @change="readData">
              <a-select-option value="aktif">Aktif</a-select-option>
              <a-select-option value="non_aktif">Non Aktif</a-select-option>
            </a-select>
          </a-col>
          <a-col class="w-full md:w-auto">
            <a-select v-model:value="filter.roles" class="min-w-32 lg:w-64 w-full" mode="multiple" show-search
              option-filter-prop="title" allow-clear @change="readData" placeholder="Cari role ...">
              <a-select-option v-for="obj in constant.ROLE" :key="obj.id" :value="obj.id" :title="obj.name">
                {{ obj.display_name }}
              </a-select-option>
            </a-select>
          </a-col>
          <a-col class="w-full md:w-auto">
            <a-input v-model:value="filter.search" @keyup.enter="readData" class="min-w-32 lg:w-64 w-full"
              placeholder="Cari pengguna ...">
              <template #addonAfter>
                <span @click="readData" class="text-white text-base">
                  <Icon icon="ant-design:search-outlined" />
                </span>
              </template>
            </a-input>
          </a-col>
          <a-col class="w-full md:w-auto">
            <a-button type="primary"
              class="bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:from-yellow-400 hover:via-yellow-600 hover:to-purple-500 flex items-center justify-center w-full"
              @click="syncData">
              <Icon icon="la:sync" class="mr-1" />
              Sinkronisasi ke sistem SDM
            </a-button>
          </a-col>
          <a-col class="w-full md:w-auto">
            <a-button type="primary"
              class="bg-gradient-to-r from-purple-400 via-blue-500 to-blue-700 hover:from-yellow-400 hover:via-yellow-600 hover:to-purple-500 flex items-center justify-center text-white font-medium border-0 shadow-md transition-all duration-300 w-full"
              @click="newData">
              <Icon icon="line-md:plus" class="mr-1" />
              Tambah Akun
            </a-button>
          </a-col>
        </a-row>
      </a-col>
    </a-row>
    <div class="mb-2 font-medium">
      Total: {{ _pagination.total }} Data
    </div>
    <a-table :scroll="{ x: 800 }" :columns="columns" :row-key="(obj) => obj.id" :pagination="_pagination"
      :loading="loadingStatus" :data-source="models" @change="handleTableChange">
      <template #bodyCell="{ index, column, record }">
        <template v-if="column.key == 'action' && filter.status == 'aktif'">
          <a-button-group class="flex justify-center">
            <!-- Edit -->
            <a-tooltip title="Edit Data">
              <a-button v-if="record.id != 1" size="small" type="text" @click="editData(record)"
                :style="{ padding: '0 5px' }">
                <Icon icon="line-md:pencil-twotone" class="flex justify-center text-green-500 text-[24px]" />
              </a-button>
            </a-tooltip>
            <!-- Detail -->
            <a-tooltip title="Lihat Detail">
              <a-button size="small" type="text" @click="openDetail(record.id)" :style="{ padding: '0 5px' }">
                <Icon icon="line-md:file-search-twotone" class="flex justify-center text-blue-500 text-[24px]" />
              </a-button>
            </a-tooltip>
            <!-- Hapus -->
            <a-tooltip title="Hapus Data">
              <a-popconfirm v-if="record.id != 1" title="Yakin menghapus data?"
                @confirm="deleteData(record.id, 'delete')">
                <a-button type="text" size="small" :style="{ padding: '0 5px' }">
                  <Icon icon="line-md:trash" class="flex justify-center text-red-500 text-[24px]" />
                </a-button>
              </a-popconfirm>
            </a-tooltip>
          </a-button-group>
        </template>
        <template v-if="column.key == 'action' && filter.status == 'non_aktif'">
          <a-popconfirm title="Yakin merestore data?" @confirm="deleteData(record.id, 'restore')">
            <a-button size="small" type="primary">
              <Icon icon="ant-design:rollback-outlined" />
            </a-button>
          </a-popconfirm>
        </template>
        <template v-if="column.key === 'number'">
          {{ (_pagination.current - 1) * _pagination.pageSize + (index + 1) }}
        </template>
        <template v-if="column.key === 'position'">
          <a-tag color="pink">
            {{ record.position }}
          </a-tag>
        </template>
        <template v-if="column.key == 'roles'">
          <a-tag v-for="role in record.roles" :key="role.id" color="#2db7f5">
            {{ role.display_name }}
          </a-tag>
        </template>
      </template>
    </a-table>
  </a-card>
  <a-modal v-model:open="showModal" :title="form.id ? 'Ubah Data' : 'Tambah Data'" @ok="writeData"
    :mask-closable="false" :destroy-on-close="true">
    <a-form ref="form" :model="form" name="basic" :label-col="{ span: 6 }" :wrapper-col="{ span: 18 }">
      <a-form-item label="Roles" data-column="roles" :rules="[{ required: true }]">
        <a-select v-model:value="form.roles" mode="multiple" show-search option-filter-prop="title" allow-clear>
          <a-select-option v-for="obj in constant.ROLE" :key="obj.id" :value="obj.id" :title="obj.name">
            {{ obj.display_name }}
          </a-select-option>
        </a-select>
      </a-form-item>
      <a-form-item label="Name" data-column="name" :rules="[{ required: true }]">
        <a-input v-model:value="form.name" autocomplete="off" />
      </a-form-item>
      <a-form-item label="Email" data-column="email">
        <a-input v-model:value="form.email" autocomplete="off" />
      </a-form-item>
      <a-form-item label="Foto" data-column="photo">
        <img v-if="form.photo" :src="form.photo" style="width: 100px" alt="Tidak ditemukan" />
      </a-form-item>
    </a-form>
  </a-modal>
</template>