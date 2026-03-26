def print_header() -> None:
    print("=" * 40)
    print("  KALKULATOR SEDERHANA")
    print("=" * 40)


def print_menu(current_value: float) -> None:
    print(f"Nilai saat ini: {format_number(current_value)}")
    print("-" * 42)
    print("Pilih Menu:")
    print("  0. Masukkan Angka")
    print("  1. Penjumlahan (+)")
    print("  2. Pengurangan (-)")
    print("  3. Pengalian  (*)")
    print("  4. Pembagian  (/)")
    print("  5. Reset")
    print("  6. Keluar")
    print("-" * 42)


def format_number(value: float) -> str:
    if value == int(value):
        return str(int(value))
    return str(value)


def read_number(prompt: str = "Masukkan angka: ") -> float:
    while True:
        raw = input(prompt).strip()
        try:
            return float(raw)
        except ValueError:
            print("Input tidak valid. Masukkan angka yang benar.")


def main() -> None:
    current_value = 0.0
    print_header()

    while True:
        print_menu(current_value)
        choice = input("Input pilihan: ").strip()

        if choice == "0":
            current_value = read_number()
            print()

        elif choice == "1":
            number = read_number()
            old = current_value
            current_value = old + number
            print(
                f"Hasil: {format_number(old)} + {format_number(number)} = {format_number(current_value)}"
            )
            print()

        elif choice == "2":
            number = read_number()
            old = current_value
            current_value = old - number
            print(
                f"Hasil: {format_number(old)} - {format_number(number)} = {format_number(current_value)}"
            )
            print()

        elif choice == "3":
            number = read_number()
            old = current_value
            current_value = old * number
            print(
                f"Hasil: {format_number(old)} * {format_number(number)} = {format_number(current_value)}"
            )
            print()

        elif choice == "4":
            number = read_number()
            if number == 0:
                print("Tidak bisa membagi dengan nol. Nilai tidak berubah.")
                print()
                continue

            old = current_value
            current_value = old / number
            print(
                f"Hasil: {format_number(old)} / {format_number(number)} = {format_number(current_value)}"
            )
            print()

        elif choice == "5":
            current_value = 0.0
            print("Nilai berhasil di-reset ke 0.")
            print()

        elif choice == "6":
            print("Terima kasih telah menggunakan kalkulator!")
            break

        else:
            print("Pilihan menu tidak valid. Silakan pilih 0 sampai 6.")
            print()


if __name__ == "__main__":
    main()
