import { cookies } from "next/headers"

export async function setAdminSession() {
  const cookieStore = await cookies()
  cookieStore.set("admin-session", "authenticated", {
    httpOnly: true,
    secure: process.env.NODE_ENV === "production",
    sameSite: "strict",
    maxAge: 60 * 60 * 24, // 24 hours
  })
}

export async function getAdminSession() {
  const cookieStore = await cookies()
  return cookieStore.get("admin-session")?.value === "authenticated"
}

export async function clearAdminSession() {
  const cookieStore = await cookies()
  cookieStore.delete("admin-session")
}
