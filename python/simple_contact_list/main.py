def generate_id(contacts: list[dict]) -> int:
    if not contacts:
        return 1
    return max(contact["id"] for contact in contacts) + 1


def find_contact_by_id(contacts: list[dict], contact_id: int) -> dict | None:
    for contact in contacts:
        if contact["id"] == contact_id:
            return contact
    return None


def show_contacts(contacts: list[dict]) -> None:
    if not contacts:
        print("Belum ada kontak.")
        return

    print("-" * 42)
    print("ID | Nama            | Nomor Telepon")
    print("---|-----------------|---------------")
    for contact in contacts:
        print(f"{contact['id']:>2} | {contact['nama']:<15} | {contact['nomor_telepon']}")
    print("-" * 42)
    print(f"Total: {len(contacts)} kontak")


def add_contact(contacts: list[dict]) -> None:
    nama = input("Nama        : ").strip()
    if not nama:
        print("Nama tidak boleh kosong.")
        return

    nomor_telepon = input("No. Telepon : ").strip()
    if not nomor_telepon:
        print("Nomor telepon tidak boleh kosong.")
        return

    new_id = generate_id(contacts)
    contacts.append(
        {
            "id": new_id,
            "nama": nama,
            "nomor_telepon": nomor_telepon,
        }
    )
    print(f"Kontak berhasil ditambahkan! (ID: {new_id})")


def edit_contact(contacts: list[dict]) -> None:
    if not contacts:
        print("Belum ada kontak untuk diubah.")
        return

    raw_id = input("Masukkan ID kontak yang ingin diubah: ").strip()
    try:
        contact_id = int(raw_id)
    except ValueError:
        print("ID harus berupa angka.")
        return

    contact = find_contact_by_id(contacts, contact_id)
    if not contact:
        print(f"Kontak dengan ID {contact_id} tidak ditemukan.")
        return

    print(f"Nama saat ini        : {contact['nama']}")
    print(f"No. Telepon saat ini : {contact['nomor_telepon']}")

    new_name = input("Ubah nama? (kosongkan untuk skip): ").strip()
    new_phone = input("Ubah nomor telepon? (kosongkan untuk skip): ").strip()

    if not new_name and not new_phone:
        print("Tidak ada perubahan data.")
        return

    if new_name:
        contact["nama"] = new_name

    if new_phone:
        contact["nomor_telepon"] = new_phone

    print(f"Kontak ID {contact_id} berhasil diperbarui!")


def delete_contact(contacts: list[dict]) -> None:
    if not contacts:
        print("Belum ada kontak untuk dihapus.")
        return

    raw_id = input("Masukkan ID kontak yang ingin dihapus: ").strip()
    try:
        contact_id = int(raw_id)
    except ValueError:
        print("ID harus berupa angka.")
        return

    contact = find_contact_by_id(contacts, contact_id)
    if not contact:
        print(f"Kontak dengan ID {contact_id} tidak ditemukan.")
        return

    confirm = input(f"Yakin ingin menghapus kontak \"{contact['nama']}\"? (y/n): ").strip().lower()
    if confirm == "y":
        contacts.remove(contact)
        print("Kontak berhasil dihapus!")
    elif confirm == "n":
        print("Penghapusan dibatalkan.")
    else:
        print("Input konfirmasi tidak valid. Penghapusan dibatalkan.")


def print_menu() -> None:
    print("=" * 40)
    print("  APLIKASI CONTACT LIST")
    print("=" * 40)
    print("  1. Tampilkan Semua Kontak")
    print("  2. Tambah Kontak Baru")
    print("  3. Ubah Kontak")
    print("  4. Hapus Kontak")
    print("  5. Keluar")
    print("-" * 42)


def main() -> None:
    contacts: list[dict] = []

    while True:
        print_menu()
        choice = input("Pilih menu: ").strip()

        if choice == "1":
            show_contacts(contacts)
        elif choice == "2":
            add_contact(contacts)
        elif choice == "3":
            edit_contact(contacts)
        elif choice == "4":
            delete_contact(contacts)
        elif choice == "5":
            print("Sampai jumpa!")
            break
        else:
            print("Menu tidak valid. Silakan pilih 1 sampai 5.")

        print()


if __name__ == "__main__":
    main()
