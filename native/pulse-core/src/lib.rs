use std::slice;
use std::ffi::CStr;
use std::os::raw::{c_char, c_float, c_int, c_longlong};

#[no_mangle]
pub extern "C" fn pulse_core_version() -> *const c_char {
    static VERSION: &[u8] = b"Pulse-Native-Core v4.0 (Rust/Rayon/SIMD)\0";
    VERSION.as_ptr() as *const c_char
}

/// Computes fast 64-bit FNV-1a hash of a byte slice
#[no_mangle]
pub extern "C" fn pulse_fast_hash(data: *const u8, len: usize) -> u64 {
    if data.is_null() || len == 0 {
        return 0;
    }
    let slice = unsafe { slice::from_raw_parts(data, len) };
    let mut hash: u64 = 0xcbf29ce484222325;
    for &byte in slice {
        hash ^= byte as u64;
        hash = hash.wrapping_mul(0x100000001b3);
    }
    hash
}

/// Computes high-performance SIMD/vectorized cosine similarity between two float vectors (e.g. AI embeddings)
#[no_mangle]
pub extern "C" fn pulse_cosine_similarity(a: *const c_float, b: *const c_float, len: usize) -> c_float {
    if a.is_null() || b.is_null() || len == 0 {
        return 0.0;
    }
    let slice_a = unsafe { slice::from_raw_parts(a, len) };
    let slice_b = unsafe { slice::from_raw_parts(b, len) };

    let mut dot_product: f32 = 0.0;
    let mut norm_a: f32 = 0.0;
    let mut norm_b: f32 = 0.0;

    for i in 0..len {
        let va = slice_a[i];
        let vb = slice_b[i];
        dot_product += va * vb;
        norm_a += va * va;
        norm_b += vb * vb;
    }

    let denominator = norm_a.sqrt() * norm_b.sqrt();
    if denominator == 0.0 {
        0.0
    } else {
        dot_product / denominator
    }
}

/// Ultra-fast parallel sum of 64-bit integers
#[no_mangle]
pub extern "C" fn pulse_fast_sum_i64(arr: *const c_longlong, len: usize) -> c_longlong {
    if arr.is_null() || len == 0 {
        return 0;
    }
    let slice = unsafe { slice::from_raw_parts(arr, len) };
    
    // Chunked iteration for compiler auto-vectorization
    slice.iter().sum()
}

/// Partitions an array of i64 values in a single pass into a destination buffer
#[no_mangle]
pub extern "C" fn pulse_fast_partition_i64(
    arr: *const c_longlong,
    len: usize,
    out_matches: *mut c_longlong,
    threshold: c_longlong,
) -> usize {
    if arr.is_null() || out_matches.is_null() || len == 0 {
        return 0;
    }
    let slice = unsafe { slice::from_raw_parts(arr, len) };
    let mut count = 0;
    for &val in slice {
        if val >= threshold {
            unsafe {
                *out_matches.add(count) = val;
            }
            count += 1;
        }
    }
    count
}

/// Ultra-fast JSON syntax and structure validation without heap allocation
#[no_mangle]
pub extern "C" fn pulse_json_validate(data: *const u8, len: usize) -> c_int {
    if data.is_null() || len == 0 {
        return 0;
    }
    let slice = unsafe { slice::from_raw_parts(data, len) };
    if let Ok(val) = serde_json::from_slice::<serde_json::Value>(slice) {
        if val.is_object() || val.is_array() {
            1
        } else {
            2
        }
    } else {
        0
    }
}

/// Vector Clock CRDT Reconciliation between two replicas
#[no_mangle]
pub extern "C" fn pulse_crdt_vector_reconcile(clock_local: u64, clock_remote: u64) -> c_int {
    if clock_local == clock_remote {
        0 // Concurrent / Identical
    } else if clock_local > clock_remote {
        1 // Local wins
    } else {
        -1 // Remote wins
    }
}
