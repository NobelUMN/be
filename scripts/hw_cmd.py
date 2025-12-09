#!/usr/bin/env python
"""
Simple Python bridge to send a command to a serial device and print response.
Usage: hw_cmd.py <device> <cmd>
Example (Windows): hw_cmd.py COM3 STATUS
Example (Linux)  : hw_cmd.py /dev/ttyUSB0 STATUS
"""
import sys
import time
import os

try:
    import serial
except Exception as e:
    print(f"ERR: missing pyserial: {e}", file=sys.stderr)
    sys.exit(2)


def main():
    if len(sys.argv) < 3:
        print("ERR: usage: hw_cmd.py <device> <cmd>", file=sys.stderr)
        sys.exit(1)

    device = sys.argv[1]
    cmd = sys.argv[2]
    baud = int(os.environ.get('HW_BAUDRATE', '115200'))

    try:
        # On Windows, device like 'COM3' works. On Linux use '/dev/ttyUSB0'.
        with serial.Serial(device, baud, timeout=2) as ser:
            ser.reset_input_buffer()
            ser.reset_output_buffer()
            ser.write((cmd + "\n").encode())
            # small wait to let device respond
            time.sleep(0.15)
            resp = ser.read_all().decode(errors='ignore')
            print(resp.strip())
    except Exception as e:
        print(f"ERR:{e}", file=sys.stderr)
        sys.exit(1)


if __name__ == '__main__':
    main()
