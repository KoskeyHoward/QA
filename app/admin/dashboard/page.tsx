import { getAdminSession } from "@/lib/auth"
import { redirect } from "next/navigation"
import { AdminDashboard } from "@/components/admin-dashboard"

export default async function AdminDashboardPage() {
  const isAdmin = await getAdminSession()

  if (!isAdmin) {
    redirect("/admin/login")
  }

  return <AdminDashboard />
}
