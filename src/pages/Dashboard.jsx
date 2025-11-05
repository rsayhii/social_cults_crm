import React from "react";
// import { base44 } from "./api/base44Client";
// import { useQuery } from "@tanstack/react-query";
import { DollarSign, Users, Megaphone, TrendingUp } from "lucide-react";

import StatsCard from "../components/dashboard/StatsCard";
import RevenueChart from "../components/dashboard/RevenueChart";
import PipelineChart from "../components/dashboard/PipelineChart";
import RecentActivity from "../components/dashboard/RecentActivity";

export default function Dashboard() {
//   const { data: clients = [], isLoading: clientsLoading } = useQuery({
//     queryKey: ['clients'],
//     queryFn: () => base44.entities.Client.list('-created_date'),
//     initialData: [],
//   });

//   const { data: campaigns = [], isLoading: campaignsLoading } = useQuery({
//     queryKey: ['campaigns'],
//     queryFn: () => base44.entities.Campaign.list('-created_date'),
//     initialData: [],
//   });

//   const { data: tasks = [], isLoading: tasksLoading } = useQuery({
//     queryKey: ['tasks'],
//     queryFn: () => base44.entities.Task.list('-created_date', 50),
//     initialData: [],
//   });

//   const totalRevenue = campaigns.reduce((sum, c) => sum + (c.revenue || 0), 0);
//   const activeClients = clients.filter(c => c.status === 'client').length;
//   const activeCampaigns = campaigns.filter(c => c.status === 'active').length;
//   const totalROI = campaigns.reduce((sum, c) => {
//     const roi = c.budget > 0 ? ((c.revenue - c.spent) / c.spent * 100) : 0;
//     return sum + roi;
//   }, 0) / (campaigns.length || 1);

  return (
    // <div className="p-4 lg:p-8 pb-24 lg:pb-8">
    //     <h1>ghjgj</h1>
    //   <div className="max-w-7xl mx-auto space-y-6 lg:space-y-8">
    //     <div>
    //       <h2 className="text-2xl lg:text-3xl font-bold text-slate-900">Dashboard Overview</h2>
    //       <p className="text-slate-500 mt-2">Track your marketing performance and client relationships</p>
    //     </div>

    //     <div className="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-6">
    //       <StatsCard
    //         title="Total Revenue"
    //         value={`$${totalRevenue.toLocaleString()}`}
    //         icon={DollarSign}
    //         trend="12.5%"
    //         trendUp={true}
    //         colorClass="bg-green-500"
    //       />
    //       <StatsCard
    //         title="Active Clients"
    //         value={activeClients}
    //         icon={Users}
    //         trend="8.2%"
    //         trendUp={true}
    //         colorClass="bg-blue-500"
    //       />
    //       <StatsCard
    //         title="Active Campaigns"
    //         value={activeCampaigns}
    //         icon={Megaphone}
    //         trend="15.3%"
    //         trendUp={true}
    //         colorClass="bg-purple-500"
    //       />
    //       <StatsCard
    //         title="Average ROI"
    //         value={`${totalROI.toFixed(1)}%`}
    //         icon={TrendingUp}
    //         trend="3.1%"
    //         trendUp={false}
    //         colorClass="bg-orange-500"
    //       />
    //     </div>

    //     <div className="grid lg:grid-cols-2 gap-4 lg:gap-6">
    //       <RevenueChart campaigns={campaigns} />
    //       <PipelineChart clients={clients} />
    //     </div>

    //     <RecentActivity tasks={tasks} clients={clients} campaigns={campaigns} />
    //   </div> 

      
    // </div>

    <>
        <h1>jgh</h1>
    </>
    
  );
}